<?php // require_once('etc/config.php') ?>


<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="en">
  
<?php include('inc/head.php') ?>
  
  <body>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header mdl-color--<?php global $theme; echo $theme->color_accent->base; ?>-800 mdl-color-text--grey-200">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title"><?php echo PAGE_NAME ?></span>
          <div class="mdl-layout-spacer"></div>
          <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
            <li class="mdl-menu__item">About</li>
            <li class="mdl-menu__item">Contact</li>
            <li class="mdl-menu__item">Legal information</li>
          </ul>
        </div>
      </header>
	  <?php include('inc/menu.php'); ?>
      <main class="mdl-layout__content mdl-color--grey-100 height: 100%;">
		<div id='maincontent' style='margin: 10px;'>
