<?php return [
  'models'=> [
      'context' => ['page','product'],
      'policies' => [
          'page' => ['bay']
      ],
      'template' => [
          'page' => ['tpl.main','tpl.landing']
      ],
      'fields' => [
          'page' => [
               [
                   'lable' => 'Color',
                   'name' => 'title',
                   'type' => 'text'
               ],
               [
                   'lable' => 'Men',
                   'name' => 'men',
                   'type' => 'text'
               ]
          ]
      ],
      'action' => [
          'page' => [
              [
                  'lable' => 'Comments',
                  'href'=> 'comments/?comment_id=:id&comment_type=App\Page',
              ],[
                  'lable' => 'Sitemap',
                  'href'=> 'sitemaps/?loc=:url',
              ]
          ]
      ]
  ]
];