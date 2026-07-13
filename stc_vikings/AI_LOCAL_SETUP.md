# Local AI Data Assistant (Ollama)

Yeh tool **sirf local XAMPP** par chalane ke liye hai. Server par deploy mat karo.

## 1. Ollama install karo (Windows)

1. Download: https://ollama.com/download
2. Install karke Ollama app open rakho (background me chalega)
3. PowerShell / CMD me model pull karo:

```bash
ollama pull llama3.2
```

Chhota / fast model chahiye ho to:

```bash
ollama pull mistral
```

Phir `stc_vikings/kattegat/ai_local_config.php` me `'model' => 'mistral'` set karo.

## 2. Page open karo

Browser me login karke kholo:

```
http://localhost/stc/stc_vikings/ai-data-assistant.php
```

Green status = Ollama connected.

## 3. Kaise use karein

| Step | Kya karo |
|------|----------|
| 1 | Problem likho — Hindi ya English dono chalega |
| 2 | AI SELECT SQL suggest karega → **Run SELECT** dabao |
| 3 | Data sahi lage to INSERT/UPDATE SQL lo → **Export .sql** |
| 4 | Export file: `stc_vikings/ai_exports/` folder me save hogi |
| 5 | Local phpMyAdmin me test karo |
| 6 | Baad me Laravel migration bana ke server par migrate karo |

## 4. Example prompts

```
stc_product table me "cement" naam wale duplicate products dikhao
```

```
stc_merchant me naya vendor add karna hai: ABC Traders, phone 9876543210 — INSERT SQL banao
```

```
stc_purchase_product_adhoc ki last 10 entries dikhao
```

## 5. Config (`kattegat/ai_local_config.php`)

| Key | Default | Meaning |
|-----|---------|---------|
| `ollama_url` | `http://127.0.0.1:11434` | Local Ollama |
| `model` | `llama3.2` | Model name |
| `allow_write_sql` | `false` | `true` = INSERT/UPDATE direct DB par (risky) |
| `allowed_tables` | purchase/product tables | Sirf in tables par query |

## 6. Laravel migration (baad me)

Export ki `.sql` file se Laravel me:

```bash
php artisan make:migration fix_local_data_export
```

Migration file me exported SQL paste karo (ya seeders use karo). Production par pehle backup lo.

## 7. Security

- `ai_local_config.php` aur `ai_exports/` server par upload mat karo
- `.gitignore` me `ai_exports/*.sql` ignore hai — sensitive data commit mat karo
- Production me is page ka route mat banao jab tak Laravel version ready na ho

## Troubleshooting

| Problem | Fix |
|---------|-----|
| Ollama offline | Ollama app start karo, `ollama list` check karo |
| Model not pulled | `ollama pull llama3.2` |
| Slow response | Chhota model use karo (`mistral`, `llama3.2:1b`) |
| Wrong SQL | Pehle SELECT chalao, phir fix SQL export karo |
