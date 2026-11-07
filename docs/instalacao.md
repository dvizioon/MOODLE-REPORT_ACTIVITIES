# Instalação

## Compatibilidade

| Campo | Valor |
|-------|-------|
| Versão do plugin | 1.3.8 |
| Build | `2026110500` |
| Moodle | **4.0 até 5.2** |
| Maturidade | Stable |

Detalhes por versão: [Compatibilidade](compatibilidade.md).

## Caminho da pasta

| Moodle | Pasta |
|--------|-------|
| 4.x | `report/activities/` |
| 5.1+ | `public/report/activities/` |

O nome da pasta deve ser **`activities`** (frankenstyle `report_activities`).

## Passo a passo

1. Baixe o zip do Marketplace ou da [release no GitHub](https://github.com/dvizioon/MOODLE-REPORT-ACTIVITIES/releases).
2. Extraia e copie a pasta `activities` para o caminho acima.
3. Acesse **Administração do site / Notificações** e conclua a atualização.

Ou pela CLI, na raiz do Moodle:

```bash
php admin/cli/upgrade.php
```

No Moodle 5.1+:

```bash
php public/admin/cli/upgrade.php
```

## Depois de instalar

1. Abra **Administração do site / Relatórios / Construtor de relatórios**.
2. Crie um novo relatório.
3. Escolha a fonte **Atividades** ou **Nota final**.

Não há item extra em **Administração do site / Relatórios** só para este plugin (removido na 1.3.8). O acesso é pelo Report Builder.

## Capacidade

Quem usa as fontes precisa de `report/activities:view` (contexto do sistema). Por padrão, gestores e criadores de curso têm essa capacidade.

Guia completo: [Como usar](guia/uso.md).
