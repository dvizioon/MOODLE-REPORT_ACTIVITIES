# Introdução

[Índice](index) · [English](../en/intro)

**Report Activities** adiciona fontes de dados ao Construtor de Relatórios do Moodle. Não substitui nem duplica a interface nativa de relatórios: as colunas, filtros, condições, audiências, agendamentos e exportações (CSV/Excel) permanecem recursos do próprio Moodle.

## Fontes disponíveis

| Fonte | Granularidade | Uso recomendado |
|-------|---------------|-----------------|
| **Atividades** | Um registro por estudante e atividade avaliada | Acompanhar notas de quizzes, tarefas e demais atividades com nota |
| **Nota final** | Um registro por estudante e curso | Obter o total do curso conforme o caderno de notas |

## Fluxo de dados

1. O estudante conclui uma atividade avaliada.
2. O módulo registra a nota no **caderno de notas** do Moodle.
3. O plugin lê esses dados e os disponibiliza como fonte no Construtor de Relatórios.

```text
Atividade avaliada
        │
        ▼
Caderno de notas (itens e notas)
        │
        ▼
Construtor de Relatórios
  (fontes Report Activities)
```

## Limites do escopo

- Não recalcula pesos, fórmulas ou agregações do curso; utiliza os valores já armazenados no caderno.
- Não inclui atividades sem item de nota (por exemplo página, arquivo, URL ou rótulo).
- Não substitui o relatório do avaliador no contexto do curso; destina-se a relatórios personalizados no Construtor de Relatórios.

Continuar em [Instalação](instalacao).
