# Como usar

[Índice](index) · [English](../en/usage)

## Criar um relatório

1. Abra **Administração do site → Relatórios → Construtor de relatórios → Relatórios personalizados**.
2. Selecione **Novo relatório**.
3. Escolha a fonte **Atividades** ou **Nota final**.
4. Configure colunas, filtros e condições.
5. Defina o acesso (audiência) e, se necessário, agendamentos e exportação.

![Painel de condições](https://raw.githubusercontent.com/dvizioon/MOODLE-REPORT-ACTIVITIES/master/screenshots/create-conditions.png)

*Condições no editor do relatório*

![Aba Acesso](https://raw.githubusercontent.com/dvizioon/MOODLE-REPORT-ACTIVITIES/master/screenshots/access-report.png)

*Controle de acesso ao relatório*

![Visualização em cartões](https://raw.githubusercontent.com/dvizioon/MOODLE-REPORT-ACTIVITIES/master/screenshots/grid-view.png)

*Resultados em visualização de cartões*

## Colunas iniciais da fonte Atividades

Ao criar um relatório com a fonte **Atividades**, o plugin sugere as seguintes colunas:

1. Nome completo do usuário (com link)
2. Nome completo do curso (com link)
3. Nome da atividade
4. Tipo da atividade
5. Porcentagem
6. Nota na escala de 0 a 10
7. Nota final da atividade

As colunas podem ser alteradas a qualquer momento no editor do relatório.

## Filtros e condições

Utilize os filtros e condições do Construtor de Relatórios para restringir o conjunto de dados (por curso, usuário, tipo de atividade e demais campos disponíveis).

## Permissão de uso

Sem a capacidade `report/activities:view`, as fontes do plugin não são exibidas no seletor.

## Página de entrada do plugin

O arquivo `index.php` do plugin exige autenticação e a capacidade mencionada, e redireciona para o Construtor de Relatórios. O fluxo usual de criação e edição de relatórios permanece no menu administrativo do Report Builder.
