# Módulo Magento 2: UrlManage

## Descrição

O módulo **UrlManage** é uma extensão para Magento 2 que adiciona funcionalidades relacionadas à manipulação de URLs, Meta Tags `hreflang` e suporte para lojas multistore.

## Funcionalidades

1. **Bloco Customizado no Head da Página:**
   O módulo adiciona um bloco personalizado ao head da página em todas as páginas do frontend.

2. **Meta Tags `hreflang`:**
   Para configurações multistore, o módulo gera automaticamente Meta Tags `hreflang` no head da página. Cada Meta Tag é associada a uma store-view específica e exibe o idioma da loja.

*Como Funciona*
Bloco Customizado:
O bloco customizado pode ser adicionado a qualquer template de página no Magento 2. Basta chamar $block->getCustomContent() para exibir o conteúdo personalizado.

*Meta Tags hreflang:*
As Meta Tags hreflang são adicionadas automaticamente ao head da página para configurações multistore. Cada Meta Tag exibe o idioma da loja e é associada à URL da página CMS específica para cada store-view.

*Configuração Multistore:*
Lembre-se de configurar as lojas e store-views corretamente no painel de administração do Magento 2 para aproveitar as Meta Tags hreflang em uma configuração multistore.

*Páginas CMS:*
Lembrep-se de configurar as páginas CMS com identificadores únicos para cada store-view.