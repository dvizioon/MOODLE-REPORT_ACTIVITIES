# Introdução

O Moodle já tem o **Construtor de Relatórios** (Report Builder). Este plugin **não** cria outra tela de relatório.

Ele registra duas **fontes de dados** dentro do Report Builder:

| Fonte | Uma linha significa | Quando usar |
|-------|---------------------|-------------|
| **Atividades** (`Activities`) | Um aluno × uma atividade com nota | Notas por Quiz, Tarefa, SCORM, etc. |
| **Nota final** (`Final grade`) | Um aluno × um curso | Total do caderno de notas do curso |

Com isso você monta relatórios personalizados com colunas, filtros, condições, audiência, cronogramas e exportação CSV/Excel — tudo recurso do core Moodle.

## Como funciona (visão rápida)

```text
Atividade (quiz, assign…)
        │
        │ envia nota
        ▼
Caderno de notas (grade_items / grade_grades)
        │
        ▼
Report Builder ← fontes do report_activities
```

1. O aluno conclui uma atividade avaliada.
2. O módulo grava a nota no **caderno de notas**.
3. O plugin lê essas tabelas e expõe os campos no Report Builder.

## O que este plugin não faz

- Não recalcula pesos ou fórmulas do curso (usa o valor já armazenado).
- Não lista páginas, arquivos, URLs ou rótulos (sem item de nota).
- Não substitui o relatório do professor no curso; é relatório **customizado** no site.

Próximo passo: [Instalação](instalacao.md).
