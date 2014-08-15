<?php

/**
 * miniShop
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de/miniShop
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

use phpManufaktur\Basic\Control\CMS\EmbeddedAdministration;

// grant the ROLE hierarchy for the miniShop ROLES
$roles = $app['security.role_hierarchy'];
if (!in_array('ROLE_MINISHOP_ADMIN', $roles)) {
    $roles['ROLE_ADMIN'][] = 'ROLE_MINISHOP_ADMIN';
    $roles['ROLE_MINISHOP_ADMIN'][] = 'ROLE_MEDIABROWSER_ADMIN';
    $app['security.role_hierarchy'] = $roles;
}

// add a access point for miniShop
$entry_points = $app['security.role_entry_points'];
$entry_points['ROLE_ADMIN'][] = array(
    'route' => '/admin/minishop',
    'name' => 'miniShop',
    'info' => $app['translator']->trans('miniShop for the kitFramework'),
    'icon' => array(
        'path' => '/extension/phpmanufaktur/phpManufaktur/miniShop/extension.jpg',
        'url' => MANUFAKTUR_URL.'/miniShop/extension.jpg'
    )
);
$app['security.role_entry_points'] = $entry_points;

// add all ROLES provided and used by the miniShop
$roles = array(
    'ROLE_MINISHOP_ADMIN'
);
$roles_provided = $app['security.roles_provided'];
if (!in_array($roles, $roles_provided)) {
    foreach ($roles as $role) {
        if (!in_array($role, $roles_provided)) {
            $roles_provided[] = $role;
        }
    }
    $app['security.roles_provided'] = $roles_provided;
}

/**
 * Use the EmbeddedAdministration feature to connect the extension with the CMS
 *
 * @link https://github.com/phpManufaktur/kitFramework/wiki/Extensions-%23-Embedded-Administration
 */
$app->get('/minishop/cms/{cms_information}', function ($cms_information) use ($app) {
    $administration = new EmbeddedAdministration($app);
    return $administration->route('/admin/minishop', $cms_information);
});

/**
 * ADMIN routes
 */

$admin->get('/minishop/setup',
    'phpManufaktur\miniShop\Data\Setup\Setup::Controller');
$admin->get('/minishop/update',
    'phpManufaktur\miniShop\Data\Setup\Update::Controller');
$admin->get('/minishop/uninstall',
    'phpManufaktur\miniShop\Data\Setup\Uninstall::Controller');


$app->get('/admin/minishop',
    'phpManufaktur\miniShop\Control\Admin\Admin::ControllerSelectDefaultTab');
$app->get('/admin/minishop/about',
    'phpManufaktur\miniShop\Control\Admin\About::Controller');

$app->get('/admin/minishop/base/list',
    'phpManufaktur\miniShop\Control\Admin\Base::Controller');
$app->get('/admin/minishop/base/edit/id/{base_id}',
    'phpManufaktur\miniShop\Control\Admin\Base::ControllerEdit')
    ->assert('base_id', '\d+')
    ->value('base_id', -1);
$app->post('/admin/minishop/base/edit/check',
    'phpManufaktur\miniShop\Control\Admin\Base::ControllerEditCheck');

$app->get('/admin/minishop/group/list',
    'phpManufaktur\miniShop\Control\Admin\Group::Controller');
$app->get('/admin/minishop/group/edit/id/{group_id}',
    'phpManufaktur\miniShop\Control\Admin\Group::ControllerEdit')
    ->assert('group_id', '\d+')
    ->value('group_id', -1);
$app->post('/admin/minishop/group/edit/check',
    'phpManufaktur\miniShop\Control\Admin\Group::ControllerEditCheck');

$app->get('/admin/minishop/article/list',
    'phpManufaktur\miniShop\Control\Admin\Article::Controller');