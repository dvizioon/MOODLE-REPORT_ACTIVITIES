#!/usr/bin/env bash
# Scan estático de anti-padrões de segurança (APP-MOODLE).
# Uso: ./security-scan.sh [caminho/do/plugin]
# Exit 1 se achar críticoidade alta.
set -euo pipefail

ROOT="${1:-.}"
ROOT="$(cd "$ROOT" && pwd)"
FAIL=0
WARN=0

red() { printf '\033[31m%s\033[0m\n' "$*"; }
yel() { printf '\033[33m%s\033[0m\n' "$*"; }
ok()  { printf '\033[32m%s\033[0m\n' "$*"; }

scan_crit() {
  local title="$1" pattern="$2"
  local hits
  hits="$(grep -RInE --include='*.php' -e "$pattern" "$ROOT" 2>/dev/null | grep -v '/vendor/' | grep -v '/node_modules/' || true)"
  if [[ -n "$hits" ]]; then
    red "CRITICO: $title"
    echo "$hits" | head -n 40
    FAIL=1
  else
    ok "OK: $title"
  fi
}

scan_warn() {
  local title="$1" pattern="$2"
  local hits
  hits="$(grep -RInE --include='*.php' -e "$pattern" "$ROOT" 2>/dev/null | grep -v '/vendor/' | grep -v '/node_modules/' || true)"
  if [[ -n "$hits" ]]; then
    yel "AVISO: $title"
    echo "$hits" | head -n 40
    WARN=1
  else
    ok "OK: $title"
  fi
}

echo "== Security scan: $ROOT =="

# Críticos (padrões da análise NTI / Anti-Padroes)
scan_crit "curl_init cru (use \\\\curl do core)" 'curl_init\s*\('
scan_crit "SSL verify desligado" 'CURLOPT_SSL_VERIFYPEER["'\'']?\s*,\s*false|SSL_VERIFYPEER.*=\s*0'
scan_crit "LIMIT/OFFSET literal no SQL" "LIMIT\s+[\$\"'{]|OFFSET\s+[\$\"'{]"
scan_crit "print_error obsoleto" '\bprint_error\s*\('
scan_crit "Grava PHP em dirroot" 'dirroot\s*\.\s*["'\''][^"'\'']+\.php|file_put_contents\s*\(\s*\$CFG->dirroot'

# Authz / CSRF / API (heurística — revise manualmente)
scan_warn "catch (Exception) em namespace — prefira \\\\Throwable" 'catch\s*\(\s*Exception\s+'
scan_warn "ajax => true em services (revisar capability no execute)" "['\"]ajax['\"]\s*=>\s*true"
scan_warn "echo/var_dump de password" 'echo\s+.*(\$|).*pass(word|wd)|var_dump\s*\(.*pass'
# External: execute() sem validate_context (caso oxywebapi / oxymoodlecache)
while IFS= read -r -d '' f; do
  if grep -qE 'function\s+execute\s*\(' "$f" && ! grep -q 'validate_context' "$f"; then
    yel "AVISO: $f tem execute() sem validate_context"
    WARN=1
  fi
done < <(find "$ROOT" -path '*/classes/external/*.php' -print0 2>/dev/null || true)

# Privacy
if ! find "$ROOT" -path '*/classes/privacy/provider.php' | grep -q .; then
  yel "AVISO: sem classes/privacy/provider.php"
  WARN=1
else
  ok "OK: privacy provider presente"
fi

# Secrets óbvios
scan_crit "Possível senha/token hardcoded" '(password|passwd|api[_-]?key|secret|token)\s*=\s*["'\''][^"'\'']{8,}["'\'']'

echo
if [[ "$FAIL" -ne 0 ]]; then
  red "Security scan FALHOU (críticos)."
  exit 1
fi
if [[ "$WARN" -ne 0 ]]; then
  yel "Security scan passou com avisos — revise manualmente."
  exit 0
fi
ok "Security scan limpo."
exit 0
