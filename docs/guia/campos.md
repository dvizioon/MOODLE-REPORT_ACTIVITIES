# Campos de nota

## Atividade × caderno

| Campo | O que é | Origem |
|-------|---------|--------|
| **Nota bruta** | O que o módulo enviou | `grade_grades.rawgrade` |
| **Nota final** (da atividade) | Valor oficial do item no caderno (após normalização, override, etc.) | `grade_grades.finalgrade` |
| **Porcentagem** | Posição entre min e max do item | calculado |
| **Nota (0–10)** | Mesma proporção × 10, arredondamento half-up | calculado |
| **Feedback** | Comentário no caderno | `format_text()` + contexto do curso |
| **Nome da atividade** | Nome do item | `format_string()` + contexto do curso |

Exemplos de half-up na nota 0–10: `7.3` vira `7`; `7.5` vira `8`.

## Override do professor

Se o professor **sobrescrever** a nota no caderno:

- **Nota final** (campo do relatório) segue o caderno
- **Nota bruta** continua mostrando o que o módulo enviou

## Total do curso

Na fonte **Nota final**, o valor da célula vem de `grade_get_course_grades()`. É o total já agregado do curso, não a nota de uma atividade isolada.

## Categoria do curso

Disponível a partir do **Moodle 4.1**. No Moodle 4.0 a entidade de categoria do core ainda não existe; as demais colunas funcionam normalmente.
