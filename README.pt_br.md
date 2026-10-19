<div align="center">
  <img src="pix/logo.png" alt="report_activities logo" width="180" />
</div>

<br />

<h1 align="center">MOODLE REPORT ACTIVITIES</h1>

<p align="center">
  Fonte do <strong>Report Builder</strong> para Moodle 5.2<br />
  Aluno · Curso · Tipo de atividade · Nota da atividade · Nota final do curso · % · Nota 0-10
</p>

<p align="center">
  <img src="https://img.shields.io/badge/MOODLE-5.2-F98012?style=for-the-badge&logo=moodle&logoColor=white" />
  <img src="https://img.shields.io/badge/PLUGIN-report__activities-1B53A4?style=for-the-badge" />
  <img src="https://img.shields.io/badge/TYPE-Reports-59666C?style=for-the-badge" />
  <br />
  <img src="https://img.shields.io/badge/VERSION-1.3.7-success?style=for-the-badge" />
  <img src="https://img.shields.io/badge/BUILD-2026101900-informational?style=for-the-badge" />
  <img src="https://img.shields.io/badge/MATURITY-Stable-2ea44f?style=for-the-badge" />
  <img src="https://img.shields.io/badge/LICENSE-GPL--3.0-blue?style=for-the-badge" />
</p>

<p align="center">
  <a href="README.md"><strong>English</strong></a> ·
  <strong>Português (Brasil)</strong> ·
  <a href="mailto:danielmartinsjob@gmail.com">Contato</a>
</p>

---

## O que é este plugin?

O Moodle já tem o **Construtor de Relatórios (Report Builder)**. Este plugin **não inventa outra tela de relatório** - ele adiciona fontes do Report Builder: **Atividades** e **Nota final**.

Assim você monta relatórios personalizados com:

| Contexto | Exemplos de campos |
|----------|-------------------|
| **Usuário** | Nome, e-mail, matrícula, campos de perfil |
| **Curso** | Nome, shortname, categoria |
| **Atividade** | Nome da atividade, tipo (Questionário, Tarefa…), nota máx/mín do item |
| **Nota** | Nota bruta da atividade, **nota final no caderno**, %, nota 0-10, feedback |

> [!IMPORTANT]
> Tudo roda **dentro do Report Builder** (`/reportbuilder/index.php`): colunas, filtros, audiência, agendamento e exportação CSV/Excel já são do Moodle. O plugin só entrega as **fontes**.

---

## Como funciona (bem explícito)

```text
┌─────────────────┐     cria item      ┌──────────────────┐
│  Atividade      │ ───────────────►   │  Caderno de      │
│  (quiz, assign…)│                    │  notas           │
│                 │   rawgrade         │  grade_items     │
│  Nota do módulo │ ───────────────►   │  grade_grades    │
└─────────────────┘                    └────────┬─────────┘
                                                │
                                                ▼
                                       ┌──────────────────┐
                                       │ Report Builder   │
                                       │ fonte Activities │
                                       │ report_activities│
                                       └──────────────────┘
```

1. O aluno faz a atividade (ex.: questionário).
2. O módulo manda a nota para o **caderno de notas**.
3. O caderno guarda:
 - **item de nota** daquela atividade (`grade_items`, `itemtype = mod`)
 - **nota do aluno** naquele item (`grade_grades`)
4. O plugin lê isso e disponibiliza no Report Builder.

### Nota da atividade × Nota final (caderno)

| Campo no relatório | O que é de verdade | Tabela Moodle |
|--------------------|--------------------|---------------|
| **Nota bruta** (`rawgrade`) | Nota que o **módulo** enviou (valor cru da atividade) | `grade_grades.rawgrade` |
| **Nota final** (`finalgrade`) | Nota **oficial no caderno de notas** daquele item - depois de normalização, override do professor, exclusão, etc. | `grade_grades.finalgrade` |
| **Porcentagem** | Quanto a nota final representa entre min e max do item | calculado |
| **Nota (0-10)** | A mesma proporção em escala 0-10, arredondada | calculado |

> [!IMPORTANT]
> **Atividades** lê a nota de cada atividade (`itemtype = mod`).
> **Nota final** (outra fonte, no mesmo grupo) lê o total do caderno do curso (`itemtype = course`), já com fórmula e agregação. Só o valor gravado; não recalcula.

> [!NOTE]
> Se o professor **sobrescrever** a nota no livro de notas, a **Nota final** reflete o caderno. A **Nota bruta** continua mostrando o que veio do módulo.

### Quais atividades entram?

Todas que têm **item de nota** no caderno (`itemtype = mod`):

- Questionário, Tarefa, Fórum (se avaliado), Lição, Workshop, SCORM…
- **Qualquer plugin `mod_*` novo** que grave nota - aparece sozinho

**Não entram como linha:** página, arquivo, URL, rótulo (sem nota); totais de categoria. O total do curso fica na fonte **Nota final**.

---

## Screenshots

Imagens da versão **1.3.6** (`screenshots/1.3.6/`).

### Criar relatório pela fonte Atividades

Objetivo: abrir o Construtor de Relatórios e criar um relatório com a fonte **Atividades** (notas por atividade).

<p align="center">
  <img src="screenshots/1.3.6/create-grades-by-activity.png" alt="Criar relatório pela fonte Atividades" width="90%" />
  <br /><em>Diálogo Novo relatório com a fonte Atividades</em>
</p>

### Criar relatório pela fonte Nota final

Objetivo: criar um relatório com a fonte **Nota final** (total do caderno do curso).

<p align="center">
  <img src="screenshots/1.3.6/create-final-grade.png" alt="Criar relatório pela fonte Nota final" width="90%" />
  <br /><em>Diálogo Novo relatório com a fonte Nota final</em>
</p>

### Notas por atividade

Objetivo: ver as notas do aluno em cada atividade avaliada (usuário, curso, atividade, porcentagem, nota final).

<p align="center">
  <img src="screenshots/1.3.6/grades-by-activity.png" alt="Relatório de notas por atividade" width="90%" />
  <br /><em>Relatório montado com a fonte Atividades</em>
</p>

### Nota final do curso

Objetivo: ver o total do curso já calculado no caderno de notas (pesos e agregação aplicados).

<p align="center">
  <img src="screenshots/1.3.6/grades-by-gradebook.png" alt="Relatório de nota final do curso" width="90%" />
  <br /><em>Relatório montado com a fonte Nota final</em>
</p>

### Condições

Objetivo: limitar quem aparece no relatório (por exemplo por curso ou usuário).

<p align="center">
  <img src="screenshots/1.3.6/create-conditions.png" alt="Condições do relatório" width="90%" />
  <br /><em>Painel de condições no editor</em>
</p>

### Acesso

Objetivo: controlar quem pode ver o relatório personalizado.

<p align="center">
  <img src="screenshots/1.3.6/access-report.png" alt="Acesso ao relatório" width="90%" />
  <br /><em>Aba Acesso do relatório</em>
</p>

### Visualização em cards

Objetivo: apresentar os mesmos dados em layout de cards.

<p align="center">
  <img src="screenshots/1.3.6/grid-view.png" alt="Visualização em cards" width="90%" />
  <br /><em>Resultados do relatório em cards</em>
</p>

---

## Links

- [Repositório](https://github.com/dvizioon/MOODLE-REPORT-ACTIVITIES)
- [Issues / bug tracker](https://github.com/dvizioon/MOODLE-REPORT-ACTIVITIES/issues)

---

## Funcionalidades

- **Integração Report Builder** - fonte nativa, sem tela paralela.
- **Uma linha = aluno × atividade com nota** no caderno.
- Fonte separada **Nota final** para o total do curso.
- **Nota bruta** (módulo) + **nota final** (caderno).
- **Porcentagem** e **nota 0-10** (half-up: `7.3→7`, `7.5→8`).
- **Filtros / condições / audiência / cronogramas** do próprio Moodle.
- **Multi-idioma:** EN + pt_BR.
- **Privacy null provider** - não armazena dados extras.
- **Testes** PHPUnit + Behat (`@report_activities`).

## Campos de nota (detalhe)

| Campo | Origem | Uso típico |
|-------|--------|------------|
| **Nota bruta** | Atividade → caderno (`rawgrade`) | “O que o módulo tirou” |
| **Nota final** | Caderno de notas (`finalgrade`) | “O que vale no livro de notas daquela atividade” |
| **Nota máxima / mínima** | Item de nota (`grademax` / `grademin`) | Escala do item |
| **Porcentagem** | `((final - min) / (max - min)) × 100` | Ex.: `73%` |
| **Nota (0-10)** | Mesma proporção × 10, arredondada | Exibição pedagógica rápida |
| **Feedback** | Caderno | Comentário do professor |

## Tecnologias / API Moodle

- **Moodle 5.2** - `public/report/…`
- **core_reportbuilder** - datasource + entities
- **Gradebook** - `grade_items` / `grade_grades`
- **Entidades core** - `user`, `course`, `course_category`

## Estrutura do Projeto

```bash
activities/
│
├── version.php                 # report_activities · 1.3.7
├── settings.php                # Link admin → Report builder
├── index.php                   # Redirect → /reportbuilder/
├── LICENSE
├── README.md
├── README.pt_br.md
├── pix/
│   ├── logo.png / logo.svg     # Marketplace / README
│   └── monologo.svg            # Ícone monocromático do Moodle
├── db/
│   └── access.php              # report/activities:view
├── classes/
│   ├── privacy/
│   │   └── provider.php        # null_provider
│   └── reportbuilder/
│       ├── datasource/
│       │   ├── activities.php  # Fonte "Activities"
│       │   └── coursefinal.php # Fonte "Nota final"
│       └── local/entities/
│           ├── grade_item.php  # Atividade (nome, tipo, max/min)
│           ├── grade.php       # Nota bruta, final, %, 0-10, feedback
│           └── course_total.php
├── lang/
│   ├── en/report_activities.php
│   └── pt_br/report_activities.php
├── tests/
│   ├── reportbuilder/datasource/
│   │   ├── activities_test.php
│   │   └── coursefinal_test.php
│   └── behat/activities_report.feature
└── screenshots/
    ├── entrarRelatorio.png
    ├── escolherAtividade.png
    ├── contextosRelatorio.png
    ├── visaoRelatorio.png
    └── vizualizarRelatorio.png
```

## Instalação

### Via Git (recomendado)

No Moodle **5.2**, o código dos plugins fica em `public/`:

```bash
cd /caminho/do/moodle/public/report
git clone https://github.com/dvizioon/MOODLE-REPORT-ACTIVITIES.git activities
```

Depois: **Administração do site → Notificações** (ou, na raiz do Moodle):

```bash
php public/admin/cli/upgrade.php
```

### Via ZIP

1. O ZIP deve descompactar na pasta `activities/` (com `version.php` dentro).
2. **Não** faça upload de ZIP por cima se a pasta `report/activities` já existir - use Notificações.
3. Ou copie a pasta manualmente para `public/report/activities` e rode o upgrade.

### Uso rápido

1. Abra `/reportbuilder/index.php`
2. **Novo relatório** → fonte **Atividades** ou **Nota final**
3. Monte colunas (usuário, curso, atividade, nota bruta, **nota final**…), filtros e agendamentos

| Recurso | Caminho |
|---------|---------|
| Construtor | `/reportbuilder/index.php` |
| Plugin | `public/report/activities` |
| Componente | `report_activities` |
| Frankenstyle | `report_activities` |

---

## Roadmap

### Feito (v1.3.7)

- [x] Fonte **Activities** no Report Builder (Moodle 5.2)
- [x] Fonte **Nota final** (total do caderno do curso)
- [x] Contextos: usuário, curso, categoria, atividade, nota
- [x] **Nota bruta** (atividade) + **nota final** (caderno de notas)
- [x] Porcentagem e nota 0-10 (arredondamento half-up)
- [x] Filtros por curso, tipo de atividade, usuário
- [x] Lang EN + pt_BR
- [x] Privacy null provider
- [x] Screenshots + README
- [x] Testes PHPUnit + Behat (`@report_activities`)

### Próximos passos (ideias)

- [ ] Coluna opcional de **peso do item** / categoria (ainda por atividade)
- [ ] Filtro rápido “só questionários” / “só tarefas” no setup padrão
- [ ] Suporte declarado a mais versões Moodle após validação
- [ ] Documentação em vídeo (criar relatório do zero → exportar CSV)

Sugestões e bugs: [Issues](https://github.com/dvizioon/MOODLE-REPORT-ACTIVITIES/issues).

---

## Privacidade

Null provider - o plugin **não grava** dados pessoais. Só lê tabelas já existentes (`user`, `course`, `grade_*`).

## Testes

No Moodle **5.2**, os CLIs ficam em `public/admin/…`. Rode **sempre a partir da raiz do Moodle** (onde estão `config.php` e `vendor/`).

### PHPUnit

```bash
# Inicializar PHPUnit uma vez (se ainda não fez)
php public/admin/tool/phpunit/cli/init.php

# Só este plugin
vendor/bin/phpunit --testsuite report_activities_testsuite

# Ou pelo arquivo
vendor/bin/phpunit public/report/activities/tests/reportbuilder/datasource/activities_test.php
vendor/bin/phpunit public/report/activities/tests/reportbuilder/datasource/coursefinal_test.php
```

Cobre: fonte disponível no Report Builder, colunas padrão, nota final / % / 0-10.

### Behat

```bash
# Inicializar Behat (uma vez)
php public/admin/tool/behat/cli/init.php

# Features deste plugin
php public/admin/tool/behat/cli/run.php --tags=@report_activities
```

Cobre: criar relatório customizado com fonte **Activities** na UI.

Arquivos:

```
tests/
├── reportbuilder/datasource/
│   ├── activities_test.php    # PHPUnit
│   └── coursefinal_test.php   # PHPUnit
└── behat/activities_report.feature
```

## Contato

Dúvidas e suporte: [danielmartinsjob@gmail.com](mailto:danielmartinsjob@gmail.com)

**Créditos:** Desenvolvido por Daniel Estevão · **DVIZIOON**

## Licença

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue?style=for-the-badge)](https://www.gnu.org/licenses/gpl-3.0)

Plugin Moodle - **GNU GPL v3 or later** (mesmo license do Moodle).
