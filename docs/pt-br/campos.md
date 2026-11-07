# Campos de nota

[Índice](index) · [English](../en/fields)

## Relação entre atividade e caderno de notas

| Campo | Descrição | Origem |
|-------|-----------|--------|
| Nota bruta | Valor enviado pelo módulo da atividade | Caderno de notas (`rawgrade`) |
| Nota final da atividade | Valor oficial do item no caderno, após normalização e eventuais sobrescritas | Caderno de notas (`finalgrade`) |
| Porcentagem | Posição relativa da nota entre o mínimo e o máximo do item | Calculado pelo plugin |
| Nota (0 a 10) | Equivalente percentual convertido para a escala de 0 a 10 | Calculado pelo plugin |
| Feedback | Comentário associado à nota | Caderno de notas |
| Nome da atividade | Nome do item de nota | Caderno de notas |

A nota na escala de 0 a 10 utiliza arredondamento half-up (valores a partir de 0,5 sobem para o inteiro seguinte).

## Sobrescrita de nota

Quando um professor sobrescreve a nota no caderno:

- O campo **nota final** reflete o valor do caderno.
- O campo **nota bruta** mantém o valor original enviado pelo módulo.

## Total do curso

Na fonte **Nota final**, o valor exibido corresponde ao total agregado do curso no caderno de notas, obtido pela API oficial do Moodle. Não se trata da nota de uma atividade isolada.

## Categoria do curso

O campo de categoria do curso está disponível a partir do Moodle 4.1. No Moodle 4.0, as demais colunas permanecem disponíveis normalmente.
