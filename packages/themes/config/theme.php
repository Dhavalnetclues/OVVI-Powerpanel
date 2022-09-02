<?php

      return [
          /*
          |--------------------------------------------------------------------------
          | Default Active Theme
          |--------------------------------------------------------------------------
          |
          | It will assign the default active theme to be used if one is not set during
          | runtime.
          */
          'active' => ($activeTheme = 'default'),

          /*
          |--------------------------------------------------------------------------
          | Base Path
          |--------------------------------------------------------------------------
          |
          | The base path where all the themes are located.
          */
          'base_path' => base_path('themes'),

          /*
          |--------------------------------------------------------------------------
          | Front Package Path
          |--------------------------------------------------------------------------
          |
          | The package path where all the packaged modules.
          */
          'front_package_path' => base_path('themes/'.$activeTheme.'/views/packages/')
      ];