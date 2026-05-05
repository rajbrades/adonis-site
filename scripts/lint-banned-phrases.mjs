#!/usr/bin/env node
/**
 * Banned-phrase lint for Lab Intelligence compliance.
 *
 * Fails the build if any of the listed phrases appears (case-insensitive)
 * in committed copy. Policy: README.md → "Compliance — Lab Intelligence".
 *
 * Scope:
 *   include extensions: .php .html .md .json .js .css
 *   exclude dirs:       node_modules/ vendor/ wp-content/uploads/ dist/ .git/
 *   exclude files:      this script (self) and README.md (the spec doc that
 *                       lists the banned phrases as banned — it would always
 *                       false-positive otherwise).
 *
 * Add new exclusions to EXCLUDE_FILES carefully — every exclusion is a
 * compliance gap. A reviewer-readable diff in CI is the audit trail.
 *
 * Run: `node scripts/lint-banned-phrases.mjs` (or `npm run lint:copy`)
 */

import { readdir, readFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import { join, relative, sep } from 'node:path';

const ROOT = process.cwd();
const SELF_REL = relative(ROOT, fileURLToPath(import.meta.url)).split(sep).join('/');

const BANNED = [
  'AI doctor',
  'AI diagnosis',
  'AI prescription',
  'AI treatment plan',
  'guaranteed optimization',
];

const INCLUDE_EXT = new Set(['.php', '.html', '.md', '.json', '.js', '.css']);

const EXCLUDE_DIRS = new Set([
  'node_modules',
  'vendor',
  'dist',
  '.git',
  '.claude',  // Claude Code per-project tooling state, not committed copy
]);

// Path-prefix excludes (relative to repo root, forward slashes).
const EXCLUDE_PATH_PREFIXES = [
  'wp-content/uploads/',
];

// Named-file excludes (relative to repo root, forward slashes).
// Each entry below documents the banned phrases as policy — including them
// in the scan would cause an unavoidable false positive. Other prototypes
// (00-04) stay in scope so designers can't slip new banned phrases in.
const EXCLUDE_FILES = new Set([
  SELF_REL,                    // self — script holds the phrase list
  'README.md',                 // spec doc — Compliance section names the bans
  '05-lab-intelligence.html',  // design prototype — "AI doesn't" card names the bans
]);

async function walk(dir, out = []) {
  let entries;
  try {
    entries = await readdir(dir, { withFileTypes: true });
  } catch {
    return out;
  }
  for (const entry of entries) {
    const full = join(dir, entry.name);
    const rel = relative(ROOT, full).split(sep).join('/');
    if (entry.isDirectory()) {
      if (EXCLUDE_DIRS.has(entry.name)) continue;
      if (EXCLUDE_PATH_PREFIXES.some((p) => (rel + '/').startsWith(p))) continue;
      await walk(full, out);
    } else if (entry.isFile()) {
      if (EXCLUDE_FILES.has(rel)) continue;
      if (EXCLUDE_PATH_PREFIXES.some((p) => rel.startsWith(p))) continue;
      const dot = entry.name.lastIndexOf('.');
      if (dot < 0) continue;
      const ext = entry.name.slice(dot).toLowerCase();
      if (!INCLUDE_EXT.has(ext)) continue;
      out.push(rel);
    }
  }
  return out;
}

function findHits(text, needle) {
  const lower = text.toLowerCase();
  const target = needle.toLowerCase();
  const hits = [];
  let i = 0;
  while ((i = lower.indexOf(target, i)) !== -1) {
    let line = 1;
    let col = 1;
    for (let k = 0; k < i; k++) {
      if (text.charCodeAt(k) === 10) { line++; col = 1; } else { col++; }
    }
    const start = Math.max(0, i - 20);
    const end = Math.min(text.length, i + needle.length + 20);
    const snippet = text.slice(start, end).replace(/\s+/g, ' ').trim();
    hits.push({ line, col, snippet });
    i += target.length;
  }
  return hits;
}

async function main() {
  const files = await walk(ROOT);
  const violations = [];

  for (const rel of files) {
    let text;
    try {
      text = await readFile(join(ROOT, rel), 'utf8');
    } catch {
      continue;
    }
    for (const phrase of BANNED) {
      const hits = findHits(text, phrase);
      for (const h of hits) {
        violations.push({ file: rel, phrase, ...h });
      }
    }
  }

  if (violations.length) {
    console.error('✗ Banned-phrase lint failed:');
    for (const v of violations) {
      console.error(`  ${v.file}:${v.line}:${v.col}  "${v.phrase}"  …${v.snippet}…`);
    }
    console.error(`\n${violations.length} violation(s) across ${files.length} scanned files.`);
    console.error('Policy: README.md → "Compliance — Lab Intelligence".');
    process.exit(1);
  }

  console.log(`✓ 0 violations across ${files.length} scanned files.`);
}

main().catch((err) => {
  console.error('Lint script error:', err);
  process.exit(2);
});
