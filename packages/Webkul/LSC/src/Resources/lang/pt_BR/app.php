<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Cache LiteSpeed',
        ],

        'acl' => [
            'title' => 'Cache LiteSpeed',
            'view'  => 'Visualizar',
            'purge' => 'Limpar',
        ],

        'page' => [
            'title' => 'Cache LiteSpeed',
            'info'  => 'Revise as tags de cache e execute limpezas direcionadas para troubleshooting e manutenção em produção.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Ações rápidas',
                'info'  => 'Use ações rápidas de limpeza para a página inicial ou, quando necessário, para todo o cache.',
            ],

            'debug' => [
                'title'         => 'Cabeçalhos de debug',
                'tag-label'     => 'Tag:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Política:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Purge:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Limpar por categoria',
                'info'  => 'Busque uma categoria e limpe apenas a tag baseada em seu ID.',
            ],

            'product' => [
                'title' => 'Limpar por produto',
                'info'  => 'Busque um produto e limpe apenas a tag baseada em seu ID.',
            ],

            'tag' => [
                'title' => 'Limpar por tag',
                'info'  => 'Digite uma ou mais tags LiteSpeed separadas por vírgulas ou espaços.',
            ],

            'url' => [
                'title' => 'Limpar por URL',
                'info'  => 'Envie uma requisição de purge para um caminho de URL exato (relativo ou absoluto).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Limpar todo o cache',
            'purge-home'     => 'Limpar página inicial',
            'purge-category' => 'Limpar categoria',
            'purge-product'  => 'Limpar produto',
            'purge-tag'      => 'Limpar tag',
            'purge-url'      => 'Limpar URL',
        ],

        'badges' => [
            'id-tag'     => 'Tag de ID',
            'manual'     => 'Manual',
            'exact-path' => 'Caminho exato',
        ],

        'fields' => [
            'category' => 'Categoria',
            'product'  => 'Produto',
            'tags'     => 'Tags',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Tag alvo:',
        ],

        'placeholders' => [
            'category' => 'Buscar categorias por nome, slug ou ID',
            'product'  => 'Buscar produtos por nome',
            'tags'     => 'Exemplo: category_5 product_22 home',
            'url'      => 'Exemplo: /footwears ou https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Escolha uma categoria específica antes de enviar.',
            'product'  => 'Escolha um produto específico antes de enviar.',
            'tags'     => 'O caractere curinga (*) está bloqueado aqui. Use “Limpar todo o cache” em vez disso.',
            'url'      => 'A requisição é normalizada para um caminho storefront antes de ser enviada ao LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'Limpar todo o cache LiteSpeed? Esta é uma ação destrutiva.',
            'home'     => 'Limpar as tags de cache da página inicial?',
            'category' => 'Limpar a tag de cache da categoria selecionada?',
            'product'  => 'Limpar a tag de cache do produto selecionado?',
            'tag'      => 'Limpar a lista de tags LiteSpeed fornecida?',
            'url'      => 'Limpar o caminho de URL fornecido?',
        ],

        'flash' => [
            'purge-all' => 'Solicitação de limpeza total do cache LiteSpeed enviada.',
            'home'      => 'Solicitação de limpeza da página inicial enviada.',
            'category'  => 'Solicitação de limpeza enviada para a categoria :name.',
            'product'   => 'Solicitação de limpeza enviada para o produto :name.',
            'tags'      => 'Solicitação de limpeza de tags enviada.',
            'url'       => 'Solicitação de limpeza de URL enviada para :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configure as opções do LSCache para melhorar o desempenho.',
                'title' => 'Cache LiteSpeed',

                'configuration' => [
                    'info'  => 'Gerencie a aplicação LiteSpeed Cache e configurações relacionadas.',
                    'title' => 'Configuração',

                    'cache-application' => [
                        'info'             => 'Defina as opções da aplicação de cache.',
                        'title'            => 'Aplicação de cache',
                        'title-info'       => 'Configure o LiteSpeed Cache: habilite/desabilite o cache e defina o TTL padrão.',
                        'status'           => 'Status',
                        'default-ttl'      => 'TTL padrão (Time To Live)',
                        'default-ttl-info' => 'Defina o tempo de vida padrão dos itens em cache em segundos <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Mais informações</a>.',
                        'debug-mode'       => 'Modo de debug',
                        'debug-mode-info'  => 'Quando ativado, adiciona cabeçalhos de debug que mostram as tags LiteSpeed, decisões de cache-control e operações de purge por requisição.',
                        'cache-path'       => 'Caminho do cache',
                        'cache-path-info'  => 'Caminho completo para o diretório de cache privado do LiteSpeed. Padrão: /usr/local/lsws/cachedata/priv (Enterprise) ou /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
