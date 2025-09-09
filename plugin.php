<?php

/**
 * Vvveb
 *
 * Copyright (C) 2022  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

/*
Name: All in One Accessibility®
Slug: allinoneaccessibility
Category: tools
Url: https://skynettechnologies.com/
Description: Quick Web Accessibility Implementation with All In One Accessibility!
Thumb: allinoneaccessibility.svg
Author: Skynet Technologies USA LLC
Version: 0.1
Author url: https://skynettechnologies.com/
Settings: /admin/index.php?module=plugins/allinoneaccessibility/settings
*/

use function Vvveb\__;
use Vvveb\System\Core\View;
use Vvveb\System\Event;

if (! defined('V_VERSION')) {
	die('Invalid request!');
}

class AllinOneAccessibilityPlugin {
	function admin() {
		// add admin menu item
		$admin_path = \Vvveb\adminPath();
		Event::on('Vvveb\Controller\Base', 'init-menu', __CLASS__, function ($menu) use ($admin_path) {
			$menu['plugins']['items']['allinoneaccessibility'] = [
				'name'     => __('All in One Accessibility®'),
				'url'      => $admin_path . 'index.php?module=plugins/allinoneaccessibility/settings',
				'icon-img' => PUBLIC_PATH . 'plugins/allinoneaccessibility/allinoneaccessibility.svg',
			];
			return [$menu];
		});
	}

    function app() {
        if (Vvveb\isEditor()) {
            return;
        }

        $view      = View::getInstance();
        $template  = $view->getTemplateEngineInstance();
        $view->plugins = $view->plugins ?? [];

        // Load plugin settings
        $options = Vvveb\getSetting('allinoneaccessibility', ['header', 'footer']);
        $view->plugins['allinoneaccessibility'] = $options ?? [];

        // Build dynamic script URL (instead of using hardcoded one in JS)
        if (!empty($options)) {
            $token      = $options['license_key'] ?? '';
            $colorcode  = $options['colorcode'] ?? '';
            $position   = $options['position'] ?? 'bottom_right';
            $iconType   = $options['aioa_icon_type'] ?? '1';
            $iconSize   = $options['aioa_icon_size'] ?? 'default';
            $widgetSize = $options['widget_size'] ?? 'standard';

            $src = "https://www.skynettechnologies.com/accessibility/js/all-in-one-accessibility-js-widget-minify.js"
                . "?aioa_reg_req=true"
                . "&token=" . rawurlencode($token)
                . "&colorcode=" . rawurlencode($colorcode)
                . "&position=" . rawurlencode($position)
                . "&icon_type=" . rawurlencode($iconType)
                . "&icon_size=" . rawurlencode($iconSize)
                . "&widget_size=" . rawurlencode($widgetSize);

            echo '<script src="' . $src . '" async id="aioa-adawidget"></script>';
        }
        $template->loadTemplateFile(__DIR__ . '/app/template/common.tpl');
    }


    function __construct() {
		if (APP == 'admin') {
			$this->admin();
		} else {
			if (APP == 'app') {
				$this->app();
			}
		}
	}
}

$AllinOneAccessibilityPlugin = new AllinOneAccessibilityPlugin();
