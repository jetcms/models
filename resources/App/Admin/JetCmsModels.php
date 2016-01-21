<?php

if (Sentinel::check()) {
    if (Sentinel::hasAnyAccess('admin.menus.*', 'superadmin')) {
        Admin::menu(App\Menu::class)->icon('fa-sitemap');
    }

    if (Sentinel::hasAnyAccess('admin.pages.*', 'superadmin')) {
        Admin::menu(App\Page::class)->icon('fa-file-text-o');
    }

    if (Sentinel::hasAnyAccess('admin.tags.*', 'superadmin')) {
        Admin::menu(App\Tag::class)->icon('fa-tags');
    }

    if (Sentinel::hasAnyAccess('admin.comments.*', 'superadmin')) {
        Admin::menu(App\Comment::class)->icon('fa-comments');
    }
    if (Sentinel::hasAnyAccess('admin.sitemaps.*', 'superadmin')) {
        Admin::menu(App\Sitemap::class)->icon('fa-sitemap');
    }
}

/**
 * Menu
 */

//$user = Sentinel::findById(4);
//dd($user);
//$activation = Activation::create($user);

Admin::model('App\Menu')->title('Menu')->alias('menus')->display(function ()
{
    $display = AdminDisplay::tree();
    $display->value('lable');
    return $display;

})->createAndEdit(function ()
{
    $form = AdminForm::form();
    $form->ajax_validation(true);

    $form->horizontal(true);
    $form->label_size('col-sm-offset-4 col-sm-1');
    $form->field_size('col-sm-3');

    $form->items([
        FormItem::text('lable', 'Lable')->required(),
        FormItem::text('url', 'URL'),
        //FormItem::bsselect('url_page_id', 'URL')->model('App\Page')->display('id|title|url'),
        FormItem::text('name')->label('Name'),
        FormItem::text('accesskey')->label('Accesskey'),
        FormItem::text('tabindex')->label('Tabindex'),
        FormItem::checkbox('active')->label('Active'),
    ]);

    return $form;
});


/**
 * Page
 */

Admin::model('App\Page')->title('Pages')->alias('pages')->display(function ()
{
    $display = AdminDisplay::datatables();
    $display->with('fields');

    $display->columnFilters([
        null,
        null,
        null,
        null,
        ColumnFilter::select()->placeholder('all')->model('App\PAge')->display('context')
    ]);

    $display->columns([
        Column::checkbox(),
        Column::string('id')->label('#'),
        Column::string('title')->label('Title'),
        Column::string('alias')->label('Alias'),
        Column::string('context')->label('Context'),
        Column::custom()->label('Active')->callback(function ($instance)
        {
            return $instance->active ? '&check;' : '-';
        }),
    ]);
    return $display;
})->create(function ($id)
{
    $form = AdminForm::form();

    $form->ajax_validation(true);

    $form->horizontal(true);
    $form->label_size('col-sm-offset-4 col-sm-1');
    $form->field_size('col-sm-3');

    $form->items([
        FormItem::text('title', 'Title')->validationRules('unique:pages,title,'.$id),
        FormItem::text('alias', 'Alias')
            ->validationRules('unique:pages,alias,'.$id.',id,context,'.Request::get('context','')),
        FormItem::select('context', 'Context')->enum(config('jetcms.models.context')),
        FormItem::bsselect('user_id', 'User')
            ->model('App\User')
            ->display('email|id')
            ->defaultValue(Sentinel::check()->id)
            ->nullable(),
    ]);

    return $form;
})->edit(function ($id)
{
    $model = App\Page::find($id);

    $form = AdminForm::tabbed();
    $form->ajax_validation(true);

    $form->items(array(
        'Main' => array(

            FormItem::columns()->columns([
                [
                    FormItem::text('title', 'Title')->validationRules('unique:pages,title,'.$id),
                    FormItem::text('alias', 'Alias')
                        ->validationRules('unique:pages,alias,'.$id.',id,context,'.Request::get('context','')),

                    FormItem::textarea('description', 'Description'),
                    FormItem::chosen('tag', 'Tag')
                        ->model('App\Tag')
                        ->display('lable')
                        ->multi(true)
                        ->nullable(),
                    FormItem::icheckbox('active')->label('Active')->skin('flat'),
                ],[
                    FormItem::bsselect('menu_id', 'Menu id')
                        ->options(App\Menu::getNestedList('level_lable'))
                        ->disableSort()
                        ->nullable(),
                    FormItem::select('context', 'Context')->enum(config('jetcms.models.context')),
                    FormItem::select('template', 'Template')
                        ->enum(config('jetcms.models.template.'.$model->context,[]))
                        ->nullable()
                        ->disableSort(),
                    FormItem::select('policies', 'Policies')
                        ->enum(config('jetcms.models.policies.'.$model->context,[]))
                        ->nullable()
                        ->disableSort(),
                    FormItem::bsselect('user_id', 'User')
                        ->model('App\User')
                        ->display('email|id')
                        ->defaultValue(Sentinel::check()->id)
                        ->nullable(),
                    FormItem::image('image', 'Image')
                ],
            ]),
            FormItem::images('gallery', 'Gallery'),

        ),
        'Content' => [

            FormItem::ckeditor('content', 'Text')
        ],
        'Fields' => value(function() use ($id,$model){

            //if (!$model) {return array();}

            return [FormItem::custom()->display(function ($instance) use ($model)
            {
                $str = null;

                foreach (config('jetcms.models.fields.'.$instance->context, array()) as $val){

                    $type = $val['type'];
                    $input = FormItem::$type('field_array.'.$val['name'].'', $val['lable']);

                    $input->defaultValue($instance->field($val['name']));

                    $str .= $input;
                }
                return $str;

            })->callback(function ($instance)
            {
                $instance->fieldArray = Request::input('field_array');
            })];
        }),
        'Action' => [
            FormItem::custom()->display(function ($instance)
            {
                $str = null;
                foreach (config('jetcms.models.action.'.$instance->context, []) as $val){

                    $s = '<a class="btn btn-default btn-small" href="/'.config('admin.prefix')
                        .'/'.$val['href'].'">'.$val['lable'].'</a> ';
                    $s = str_replace(':id',$instance->id,$s);
                    $str .= str_replace(':url',$instance->url,$s);
                }
                return $str;


            })
        ]
    ));

    return $form;
});

/**
 * Tags
 */

Admin::model('App\Tag')->title('Tag')->alias('tags')->display(function ()
{
    $display = AdminDisplay::table();

    $display->columns([
        Column::checkbox(),
        Column::string('lable')->label('Lable'),
        Column::string('context')->label('Context'),
    ]);
    return $display;

})->createAndEdit(function ()
{
    $form = AdminForm::form();
    $form->ajax_validation(true);

    $form->items([
        FormItem::text('lable', 'Lable')->required(),
        FormItem::text('context', 'Context'),
    ]);
    return $form;
})->delete(null);

/**
*  Comments
*/

Admin::model('App\Comment')->title('Comments')->alias('comments')->display(function ()
{
    $display = AdminDisplay::table();
    $display->with('object','user');

    $display->filters([
        Filter::field('comment_id')->title(function ($value)
        {
            return 'Page ID:'.$value;
        })
    ]);

    $display->columns([
        Column::checkbox(),
        Column::string('content')->label('content'),
        Column::string('user.name')->label('User'),
        Column::string('comment_id')->label('comment_id'),
        Column::custom()->label('active')->callback(function ($instance)
        {
            if ($instance->active)
            {
                return ' <span><i class="fa fa-chevron-down" data-toggle="tooltip" title="" data-original-title="Active"></i></span>';
            }
        })->orderable(false),
    ]);
    return $display;

})->createAndEdit(function ()
{
    $form = AdminForm::form();
    $form->ajax_validation(true);

    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::text('lable', 'lable'),
                FormItem::bsselect('user_id', 'User')
                    ->model('App\User')
                    ->display('email')
                    ->defaultValue(Sentinel::getUser()->id),
                FormItem::icheckbox('active')->label('Active')->skin('flat'),
            ],[

                FormItem::text('comment_id', 'Page ID'),
                FormItem::text('comment_type'),
            ]

        ]),
        FormItem::ckeditor('content', 'Content'),
    ]);
    return $form;
});

/**
 *  Sitemaps
 */

Admin::model('App\Sitemap')->title('Sitemap')->alias('sitemaps')->display(function ()
{
    $display = AdminDisplay::table();

    $display->filters([
        Filter::field('loc')->title(function ($value)
        {
            return 'URL:'.$value;
        })
    ]);

    $display->columns([
        Column::checkbox(),
        Column::string('loc')->label('loc'),
        Column::string('lastmod')->label('lastmod'),
        Column::string('changefreq')->label('changefreq'),
        Column::string('priority')->label('priority'),
        Column::string('updated_at')->label('updated_at'),
        Column::custom()->label('in_sitemap')->callback(function ($instance)
        {
            if ($instance->in_sitemap)
            {
                return ' <span><i class="fa fa-chevron-down" data-toggle="tooltip" title="" data-original-title="Active"></i></span>';
            }
        })->orderable(false),
    ]);
    return $display;

})->createAndEdit(function ()
{
    $form = AdminForm::form();
    $form->ajax_validation(true);

    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::text('loc', 'loc')->required(),
                FormItem::timestamp('lastmod', 'lastmod')->required(),
                FormItem::select('changefreq', 'changefreq')->enum(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])->required(),
                FormItem::text('priority', 'priority')->required(),
                FormItem::icheckbox('in_sitemap')->label('In sitemap'),
            ]
        ]),
    ]);
    return $form;
});