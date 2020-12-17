<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validations
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'register' => [
          'email'         => 'required|string|email|unique:users',
          'password'      => 'required|string',
          'name'          => 'required|string',
          'phone'         => 'required|string',
          'association'   => 'string',
          'device_token'  => 'string',
          'profesional'   => 'boolean'
    ],

    'register_rrss' => [
          'social_token'      => 'required|string',
          'social_name'       => 'required|string',
          'social_user_name'  => 'required|string',
          'social_user_email' => 'string',
          'association'       => 'string',
          'device_token'      => 'string',
          'profileImage'      => 'string',
          'profesional'   => 'boolean'
    ],

    'login_rrss' => [
      'social_token'      => 'required|string',
      'social_name'       => 'required|string',
      'social_user_name'  => 'required|string',
      'social_user_email' => 'string',
      'device_token'      => 'string'
    ],

    'login' => [
          'email'     => 'required|string|email',
          'password'  => 'required|string',
          'device_token'  => 'string'
    ],

    'sendChat' => [
          'message'   => 'string',
          'image'     => 'image:mimes:jpg,jpeg,png'
    ],

    'blockUser' => [
        'user_id' => 'required|integer'
    ],

    'pay' => [
          'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
    ],
    'main' => [
        'cat'   => 'required|integer',
        'subcat'   => 'integer'
    ],

    'search' => [
        'category'          => 'integer',
        'expensive_first'   => 'nullable|boolean',
        'cheapest_first'    => 'nullable|boolean',
        'newest'            => 'nullable|boolean',
        'relevance'         => 'nullable|boolean',
        'only_bars'         => 'nullable|boolean',
        'only_business'     => 'nullable|boolean',
        'search'            => 'nullable|string',
        'price_from'        => 'nullable|integer',
        'price_to'          => 'nullable|integer',
        'only_business'     => 'boolean'
    ],


    'createProduct' => [
        'images'        => 'array|min:1|max:8',
        'images.*'      => 'image|mimes:jpg,jpeg,png',
        'name'          => 'required|string',
        'description'   => 'required|string',
        'price_per'     => 'required|string|in:ml,l,unit,pack_of_units,g,kg',
        'category_id'   => 'integer',
        'price'         => 'required|regex:/^\d*(\.\d{1,2})?$/',
        'offer_price'   => 'regex:/^\d*(\.\d{1,2})?$/',
        'sizes'         => 'array',

        'sizes.*.name'          => 'required|string',
        'sizes.*.description'   => 'string',
        'sizes.*.price'         => 'required|regex:/^\d*(\.\d{1,2})?$/',
        'sizes.*.offer_price'   => 'regex:/^\d*(\.\d{1,2})?$/',

        'sections'      => 'array',
          'sections.*.name'       => 'required|string',

        'hidden'        => 'required|bool',

    ],

    'editProduct' => [
        'images'        => 'array|max:8',
        'images.*'      => 'image|mimes:jpg,jpeg,png',
        'name'          => 'string',
        'offer_price'   => 'regex:/^\d*(\.\d{1,2})?$/',
        'sizes'         => 'required|array|min:1',
        'description'   => 'string',
        'price_per'     => 'string|in:ml,l,unit,pack_of_units,g,kg',
        'category_id'   => 'integer',
        'price'         => 'required|regex:/^\d*(\.\d{1,2})?$/',
        'sizes'         => 'array',
        'hidden'        => 'bool',
          'sizes.*.name'          => 'string',
          'sizes.*.description'   => 'string',
          'sizes.*.price'         => 'regex:/^\d*(\.\d{1,2})?$/',
          'sizes.*.offer_price'   => 'regex:/^\d*(\.\d{1,2})?$/',
    ],

    'createBusiness' => [
        'images'        => 'array|max:8|min:1',
        'images.*'      => 'image|mimes:jpg,jpeg,png',
        'name'          => 'required|string',
        'email'         => 'required|email',
        'phone'         => 'required|string',
        'description'   => 'required|string',
        'direction'   => 'required|string',
        'longitude'   => 'required',
        'latitude'    => 'required',
        'category_id'   => 'required|integer',
        'schedule'      => 'required|array|min:1',
          'schedule.*.open_from'        => 'required|date_format:H:i',
          'schedule.*.open_to'          => 'required|date_format:H:i',
          'schedule.*.day'              => 'required|string|in:l,m,x,j,v,s,d',

    ],

    'editBusiness' => [
        'name'          => 'string',
        'email'         => 'email',
        'phone'         => 'string',
        'description'   => 'string',
        'direction'   =>  'string',

        'category_id'   => 'integer',
        'schedule'      => 'array|min:1',
          'schedule.*.open_from'        => 'date_format:H:i',
          'schedule.*.open_to'          => 'date_format:H:i',
          'schedule.*.day'              => 'string|in:l,m,x,j,v,s,d',

    ],

    'addToCart' => [
      'quantity'    => 'integer|min:1',
      'howmuch'     => 'required',
      'size_id'     => 'integer',
      'description' => 'string',

    ],

    'editCart' => [
      'quantity'    => 'integer|min:1',
      'size_id'     => 'integer',
      'description' => 'string',
    ],

    'seeMyProducts' => [
      'hidden'    => 'boolean'
    ],

    'buy'   =>    [
    ],

    'addCreditCard'   => [
      'id'          =>  'required|string',
    ],

    'removeImage'   => [
      'image_id'        => 'required|image'
    ],

    'addImage'   => [
      'image'        => 'required|image|mimes:jpg,jpeg,png'
    ],

    'editUser'   => [
      'name'          => 'string',
      'phone'         => 'string',
      'email'         => 'string|email|unique:users',
      'direction'     => 'string',
      'profileImage'  => 'image|mimes:jpg,jpeg,png',
      'birthday'      => 'date'

    ],

    // 'requestCancelOrDeliver' => [
    //   'user_id'       =>  'required|integer'
    // ],

    'location' => [
      'longitude'   => 'required',
      'latitude'    => 'required',
    ],

    'getProducts' => [
      'invisibles'      =>  'boolean',
      'importantFirst'  =>  'boolean',
      'minPrice'        =>  'integer',
      'maxPrice'        =>  'integer',
      'search'          =>  'string'
    ],

    'report'  =>  [
      'message'    =>  'required|string'
    ],

    'removeOrders'  => [
      'ids'     =>  'required|array',
          'ids.*'        => 'required|integer'
    ],

    'resetPass'   => [
      'email'  => 'required|email'
    ],
    'changePass' => [
      'password'  => 'required|string',
      'token'     => 'required|string'
    ],
    'addReview' => [
      'text' => 'string',
      'score' => 'required|regex:/^\d*(\.\d{1,2})?$/',
    ],
    'addDiscount' => [
      'title'               => 'required|string',
      'subtitle'            => 'required|string',
      'cost_points'         => 'required|integer',
      'percentage_dicount'  => 'required|integer'

    ],
    'applyDiscount' => [
      'ids'     =>  'required|array',
        'ids.*'        => 'required|integer'
    ],
    'addNew' => [
      'title'     =>  'required|string',
      'subtitle'  =>  'string',
      'text'      => 'required|string',
      'image'     => 'image:mimes:jpg,jpeg,png'

    ],

    'removeProducts'  => [
      'ids'     =>  'required|array',
          'ids.*'        => 'required|integer'
    ],

    'reserve'  => [
      'time'     =>  'required|date_format:H:i',
      'date'      => 'required|date'.
      'take_away' => 'required|boolean',
      'num_persons' => 'required|integer',
    ],









];
