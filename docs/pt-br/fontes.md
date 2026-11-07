# Fontes de relatório

[Índice](index) · [English](../en/sources)

## Atividades

Cada linha corresponde a um estudante e a uma atividade avaliada.

Características:

- Considera itens do caderno de notas com tipo de item de módulo (`mod`)
- Abrange quizzes, tarefas, fóruns com nota, lições, workshops, SCORM e demais atividades que registrem nota
- Exclui recursos sem nota (página, arquivo, URL, rótulo)
- Não utiliza totais de categoria como linha de relatório
- O total do curso é obtido pela fonte **Nota final**

Campos disponíveis incluem usuário, curso, categoria do curso (Moodle 4.1 ou superior), nome e tipo da atividade, nota bruta, nota final da atividade, porcentagem, nota na escala de 0 a 10, feedback e data da última alteração.

![Diálogo Novo relatório com a fonte Atividades](https://raw.githubusercontent.com/dvizioon/MOODLE-REPORT-ACTIVITIES/master/screenshots/create-grades-by-activity.png)

*Criação de relatório com a fonte Atividades*

![Relatório com notas por atividade](https://raw.githubusercontent.com/dvizioon/MOODLE-REPORT-ACTIVITIES/master/screenshots/grades-by-activity.png)

*Exemplo de relatório gerado com a fonte Atividades*

## Nota final

Cada linha corresponde a um estudante e a um curso.

Características:

- Utiliza o total do caderno de notas do curso
- Apresenta o mesmo valor exibido como total do curso no relatório do avaliador
- Respeita pesos, fórmulas e agregações já aplicados pelo Moodle
- Não recalcula o total no plugin

Campos disponíveis incluem usuário, curso, categoria do curso (Moodle 4.1 ou superior) e nota final do curso.

![Diálogo Novo relatório com a fonte Nota final](https://raw.githubusercontent.com/dvizioon/MOODLE-REPORT-ACTIVITIES/master/screenshots/create-final-grade.png)

*Criação de relatório com a fonte Nota final*

![Relatório com nota final do curso](https://raw.githubusercontent.com/dvizioon/MOODLE-REPORT-ACTIVITIES/master/screenshots/grades-by-gradebook.png)

*Exemplo de relatório gerado com a fonte Nota final*

## Critério de escolha

| Objetivo | Fonte recomendada |
|----------|-------------------|
| Notas por atividade | **Atividades** |
| Total do curso | **Nota final** |

Ambas as fontes aparecem no seletor de fontes do Construtor de Relatórios, no grupo do plugin.
