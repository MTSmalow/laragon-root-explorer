# Laragon Root UI

Uma interface web moderna e customizada para substituir a listagem padrão do diretório root (`www`) do **Laragon**.

Este projeto transforma o diretório local em um **explorador web**, oferecendo melhor visualização, navegação intuitiva e recursos úteis para ambientes de desenvolvimento local.

---

## Funcionalidades

-  Navegação completa por **pastas e subpastas**
-  Breadcrumb para navegação rápida
-  Listagem de arquivos com identificação por tipo
-  Preview automático de imagens (JPG, PNG, GIF, WEBP, SVG)
-  Busca em tempo real por arquivos e pastas
-  Interface moderna (dark mode)
-  Leve, rápido e sem dependências externas
-  Proteção contra acesso fora do diretório root

---

##  Motivação

A listagem padrão de diretórios do servidor web é funcional, mas pouco amigável.

O **Laragon Root UI** foi criado para:
- Melhorar a experiência visual
- Facilitar a navegação entre projetos
- Servir como base para dashboards locais
- Explorar arquivos diretamente pelo navegador

---

##  Tecnologias utilizadas

- **PHP** (listagem segura e navegação)
- **HTML5**
- **CSS3** (layout responsivo e moderno)
- **JavaScript** (busca e interações)

Sem frameworks. Sem dependências. Apenas o essencial.

---

##  Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/laragon-root-ui.git
Copie o arquivo index.php para:
```bash
C:\laragon\www\
```

Inicie o Laragon

Acesse no navegador:

```
http://localhost
```

## Estrutura
  ```
  www/
  ├── index.php   # Interface principal
  ├── projeto1/
  ├── projeto2/
  └── arquivos/
  ```
## Segurança

- O sistema utiliza realpath para evitar path traversal
- Não permite acesso fora da pasta root configurada
- Apenas leitura (nenhuma operação destrutiva)

