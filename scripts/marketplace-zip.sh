#!/usr/bin/env bash
# ZIP do plugin para a Moodle Marketplace / Instalar plugins.
#
# A pasta do plugin e a pasta de saída ficam aqui em cima: se o checkout mudar, edite só isto.
PLUGIN_DIR="/home/daniel/Área de trabalho/Projetos/MOODLE/5.2/public/report/activities"
OUT_DIR="/home/daniel/Área de trabalho/Projetos/MOODLE/5.2/public/report/_releases"

# Não entra no ZIP (Marketplace).
EXCLUDE_DIRS=(
  .git
  .github
  screenshots
  scripts
  tests/stubs
)
EXCLUDE_FILES=(
  .DS_Store
  Thumbs.db
  '*.zip'
  '*.log'
)

set -euo pipefail

if [[ ! -f "$PLUGIN_DIR/version.php" ]]; then
  echo "version.php não encontrado em: $PLUGIN_DIR" >&2
  exit 1
fi

release="$(sed -nE "s/.*\\\$plugin->release[[:space:]]*=[[:space:]]*'([^']+)'.*/\\1/p" "$PLUGIN_DIR/version.php" | head -n 1)"
component="$(sed -nE "s/.*\\\$plugin->component[[:space:]]*=[[:space:]]*'([^']+)'.*/\\1/p" "$PLUGIN_DIR/version.php" | head -n 1)"
plugindirname="$(basename "$PLUGIN_DIR")"
release="${release:-dev}"
component="${component:-plugin}"

is_excluded() {
  local rel="$1"
  local dir
  for dir in "${EXCLUDE_DIRS[@]}"; do
    if [[ "$rel" == "$dir" || "$rel" == "$dir"/* ]]; then
      return 0
    fi
  done
  local base
  base="$(basename "$rel")"
  local pat
  for pat in "${EXCLUDE_FILES[@]}"; do
    # shellcheck disable=SC2254
    if [[ "$base" == $pat ]]; then
      return 0
    fi
  done
  return 1
}

cmd_list() {
  echo "Plugin:  $PLUGIN_DIR"
  echo "Saída:   $OUT_DIR"
  echo "Release: ${component} ${release}"
  echo
  echo "Pastas e arquivos na raiz:"
  local entry name
  for entry in "$PLUGIN_DIR"/* "$PLUGIN_DIR"/.[!.]* "$PLUGIN_DIR"/..?*; do
    [[ -e "$entry" ]] || continue
    name="$(basename "$entry")"
    if is_excluded "$name"; then
      printf '  FORA   %s\n' "$name"
    else
      printf '  ENTRA  %s\n' "$name"
    fi
  done
}

cmd_zip() {
  local outfile="$OUT_DIR/${component}-${release}.zip"
  local parent
  parent="$(dirname "$PLUGIN_DIR")"
  mkdir -p "$OUT_DIR"

  # find -prune não entra em .git / screenshots / .github (rsync ainda lista o .git e trava).
  local prune=()
  local dir
  for dir in "${EXCLUDE_DIRS[@]}"; do
    prune+=(-o -path "$plugindirname/$dir")
  done
  unset 'prune[0]'

  local file_prune=()
  local pat
  for pat in "${EXCLUDE_FILES[@]}"; do
    file_prune+=(! -name "$pat")
  done

  rm -f "$outfile"
  (
    cd "$parent"
    # shellcheck disable=SC2046
    find "$plugindirname" \( "${prune[@]}" \) -prune -o -type f "${file_prune[@]}" -print
  ) | (
    cd "$parent"
    zip -q "$outfile" -@
  )

  echo "ZIP: $outfile"
  if unzip -l "$outfile" | grep -E '/\.git/|/\.github/|/screenshots/|/scripts/|/tests/stubs/' >/dev/null; then
    echo "ERRO: o ZIP ainda contém pasta excluída" >&2
    exit 1
  fi
  unzip -l "$outfile" | sed -n '1,25p'
  echo "..."
  echo "arquivos: $(unzip -l "$outfile" | tail -n 1)"
}

usage() {
  echo "Uso: $0 list | zip"
  echo "  list  mostra o que entra e o que fica de fora"
  echo "  zip   gera ${component}-${release}.zip em OUT_DIR"
}

case "${1:-zip}" in
  list) cmd_list ;;
  zip)  cmd_list; echo; cmd_zip ;;
  -h|--help|help) usage ;;
  *) usage; exit 1 ;;
esac
