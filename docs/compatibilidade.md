# Compatibilidade

Declarado em `version.php`:

| Campo | Valor |
|-------|-------|
| `$plugin->requires` | `2022041900` (Moodle 4.0.0) |
| `$plugin->supported` | `400` a `502` |
| `$plugin->release` | `1.3.8` |
| `$plugin->version` | `2026110500` |

## Matriz prática

| Moodle | PHP no CI | O que funciona |
|--------|-----------|----------------|
| 4.0 | 8.0 | Ambas as fontes. Sem entidade de categoria de curso. |
| 4.1 – 4.4 | 8.0 / 8.1 | Ambas as fontes, com categoria. |
| 4.5 | 8.1 | Ambas as fontes. |
| 5.0 – 5.1 | 8.2 / 8.3 | Ambas as fontes. |
| 5.2 | 8.3 / 8.4 | Ambas as fontes (versão de referência). |

## Moodle 3.x

**Não suportado.** O Report Builder não existe nessas versões.

## Um único código

A 1.3.8 mantém um único código para 4.0–5.2:

- Camada `classes/reportbuilder/compat.php` + `lib.php`
- API de entidades antiga (4.0) e moderna (4.1+)
- Testes PHPUnit com bridge para `core_reportbuilder_testcase`

CI no GitHub: gate Marketplace no 5.2 + matriz de compatibilidade 4.0–5.2.
