# Privacidade

## Provider

O plugin usa **null provider** (`classes/privacy/provider.php`).

Isso significa:

- Não cria tabelas próprias com dados pessoais
- Não armazena cópia de notas, usuários ou cursos
- Apenas **lê** tabelas do core (`user`, `course`, `grade_*`) via Report Builder

## Capacidade

Acesso às fontes depende de `report/activities:view`. Sem a capacidade, as fontes não aparecem.

## LGPD / GDPR

Para políticas do site, trate este plugin como componente que **exibe** dados já existentes no Moodle (caderno de notas e perfil), sob as mesmas regras de retenção e papéis do site.
