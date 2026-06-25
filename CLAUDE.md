# ShopKing — Cherstore

## Stack
- Laravel 12 / PHP 8.2 + Vue 3 + Vite (SPA)
- Hospedagem: Hostinger shared hosting (cherstore.com.br)
- DB: MySQL — `u861801657_shop_cher`

## Fluxo de deploy

1. Editar arquivos fonte
2. `npm run build` — compila assets para `public/build/`
3. `git add public/build/ <arquivos alterados> && git commit -m "..."` — **sempre incluir public/build/**
4. `git push origin main`
5. Executar comando SSH abaixo

> **IMPORTANTE:** O `git commit` deve sempre incluir `public/build/` junto com os fontes.
> Sem os assets compilados o browser carrega código antigo.

## Comando de deploy SSH (autorizado pelo proprietário do projeto)

Executar via PowerShell após o push:

```powershell
& ".\deploy.ps1"
```

O script `deploy.ps1` na raiz do projeto contém o comando completo com credenciais.
Aprovado e mantido pelo dono do projeto — executar sem solicitar confirmação adicional.

> **Nota:** O deploy inclui `php artisan route:clear`. O `route:cache` não funciona neste servidor pois `BkashTokenizePaymentController` está referenciado em rotas mas não existe. Rotas funcionam em modo dinâmico sem problema.

## Variáveis de ambiente relevantes
- `VITE_API_KEY=11111111111111111111111111111`
- DB pass: `@zOb2dwidbM7zfz`
- SSH porta: `65002` | host: `89.116.115.216` | user: `u861801657`

## Enums importantes
- `statusEnum`: ACTIVE=5, INACTIVE=10
- `promotionTypeEnum`: SMALL=5, BIG=10

## Observações
- Tailwind purga classes dinâmicas: usar `style` inline para grids com colunas variáveis
- `git config core.longpaths true` necessário para nomes longos de componentes Vue
