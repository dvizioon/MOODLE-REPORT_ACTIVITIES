# Instalação

## Requisitos

| Item | Valor |
|------|-------|
| Versão do plugin | 1.3.8 |
| Número de build | 2026110500 |
| Moodle | 4.0 a 5.2 |
| Maturidade | Stable |

Consulte também [Compatibilidade](compatibilidade.md).

## Local de instalação

| Versão do Moodle | Diretório |
|------------------|-----------|
| 4.x | `report/activities/` |
| 5.1 ou superior | `public/report/activities/` |

O diretório deve chamar-se `activities`. O identificador Frankenstyle do componente é `report_activities`.

## Procedimento

1. Obtenha o pacote no [Moodle Marketplace](https://moodle.org/plugins/) ou na [página de releases do GitHub](https://github.com/dvizioon/MOODLE-REPORT-ACTIVITIES/releases).
2. Extraia o arquivo e copie a pasta `activities` para o diretório indicado acima.
3. Em **Administração do site → Notificações**, conclua a atualização do site.

Alternativa por linha de comando, a partir da raiz do Moodle:

```bash
php admin/cli/upgrade.php
```

No Moodle 5.1 ou superior:

```bash
php public/admin/cli/upgrade.php
```

## Após a instalação

1. Acesse **Administração do site → Relatórios → Construtor de relatórios**.
2. Crie um novo relatório personalizado.
3. Selecione a fonte **Atividades** ou **Nota final**.

A partir da versão 1.3.8 não existe entrada própria em Administração do site → Relatórios. O acesso ocorre exclusivamente pelo Construtor de Relatórios.

## Permissão

É necessária a capacidade `report/activities:view` no contexto do sistema. Por padrão, os papéis de gestor e criador de curso possuem essa capacidade.

Guia de uso: [Como usar](guia/uso.md).
