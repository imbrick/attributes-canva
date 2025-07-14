# Attributes Canva - Enhanced Edition

## 🚀 New Features Overview

The enhanced Attributes Canva theme now includes three major integrations:

### 1. 🎨 **Page Builder Integration**

- **Elementor** - Full compatibility with custom widgets
- **Beaver Builder** - Custom modules and theme integration
- **Divi** - Native module support and styling
- **Universal Support** - Automatic detection and optimization

### 2. 🔧 **Advanced Custom Fields (ACF) Integration**

- **Custom Blocks** - Hero sections, testimonials, feature grids
- **Options Pages** - Theme settings, social media, header/footer
- **Field Groups** - Page settings, post enhancements
- **Helper Functions** - Easy field retrieval and display

### 3. 🌍 **Multilingual Support**

- **WPML** - Full compatibility with string registration
- **Polylang** - Native integration and menu support
- **qTranslate** - Automatic text processing
- **Weglot** - Seamless compatibility
- **RTL Support** - Automatic right-to-left language detection

## 📁 File Structure (Enhanced)

```
attribute-canva/
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── dark-mode.css
│   │   ├── beaver-builder.css
│   │   ├── divi-builder.css
│   │   └── multilingual.css
│   └── js/
│       ├── script.js
│       ├── dark-mode.js
│       ├── ajax.js
│       └── enhanced.js
├── inc/
│   ├── theme-setup.php
│   ├── enqueue-scripts.php
│   ├── ajax-handlers.php
│   ├── customizer.php
│   ├── widgets.php
│   ├── starter-content.php
│   ├── page-builders.php
│   ├── acf-integration.php
│   ├── multilingual.php
│   ├── elementor/
│   │   └── widgets/
│   ├── beaver-builder/
│   │   └── modules/
│   └── divi/
│       └── modules/
├── template-parts/
│   ├── blocks/
│   │   ├── hero-section.php
│   │   ├── testimonial.php
│   │   ├── feature-grid.php
│   │   └── call-to-action.php
│   ├── header/
│   ├── footer/
│   └── content/
├── acf-json/
├── languages/
└── functions.php
```

## 🛠️ Installation & Setup

### Step 1: Basic Installation

1. Upload the enhanced theme files to `/wp-content/themes/attribute-canva/`
2. Activate the theme in WordPress admin
3. Install recommended plugins (see below)

### Step 2: Install Recommended Plugins

#### Essential Plugins

```bash
# Page Builders (choose one or more)
- Elementor (Free/Pro)
- Beaver Builder (Pro)
- Divi Builder (Pro)

# Custom Fields
- Advanced Custom Fields (Free/Pro)

# Multilingual (choose one)
- WPML (Pro)
- Polylang (Free/Pro)
- Weglot (Pro)
- qTranslate-XT (Free)
```

#### Optional Plugins

```bash
- One Click Demo Import (for starter content)
- Yoast SEO (for enhanced SEO)
- WooCommerce (for e-commerce)
- Contact Form 7 (for forms)
```

### Step 3: Theme Configuration

#### ACF Setup (if using ACF)

1. Go to **Custom Fields > Field Groups**
2. Import field groups from `/acf-json/` directory (automatic)
3. Configure **Theme Options** under the new menu item
4. Set up **Social Media** links
5. Customize **Header** and **Footer** settings

#### Multilingual Setup (if using WPML/Polylang)

1. Configure your languages in the multilingual plugin
2. Theme strings are automatically registered
3. Translate menus and widgets as needed
4. Use language switcher widgets or functions

#### Page Builder Setup

1. **Elementor**: Custom widgets appear in "Attributes Canva" category
2. **Beaver Builder**: Use custom modules in the builder
3. **Divi**: Custom modules available in Divi Builder

## 🎯 Usage Examples

### ACF Blocks Usage

#### Hero Section Block

```php
// In Gutenberg editor, add "Hero Section" block
// Configure in block settings:
- Hero Title: "Welcome to Our Site"
- Hero Subtitle: "Amazing experiences await"
- Hero Image: Upload background image
- Button Text: "Get Started"
- Button URL: "/contact"
```

#### Feature Grid Block

```php
// Add "Feature Grid" block
// Add features with:
- Title: "Fast Performance"
- Description: "Lightning fast loading times"
- Icon: Upload icon image
- Link: Optional link to more info
```

### ACF Helper Functions

#### Get Custom Field Value

```php
// Get field with fallback
$custom_value = attributes_canva_get_field('field_name', $post_id, 'default_value');

// Get option field
$theme_option = attributes_canva_get_option('theme_setting', 'default');
```

#### Display Social Links

```php
// In your template
echo attributes_canva_social_links();
```

#### Show Reading Time

```php
// Display reading time
attributes_canva_display_reading_time();

// Get reading time value
$reading_time = attributes_canva_reading_time();
```

### Multilingual Functions

#### Translate Strings

```php
// Translate theme strings
$translated = attributes_canva_translate('Hello World', 'Theme Context');
```

#### Language Switcher

```php
// Dropdown switcher
echo attributes_canva_language_switcher('dropdown');

// List format
echo attributes_canva_language_switcher('list');

// Flag format
echo attributes_canva_language_switcher('flags');
```

#### Check Multilingual Status

```php
if (attributes_canva_is_multilingual()) {
    $current_lang = attributes_canva_get_current_language();
    echo "Current language: " . $current_lang;
}
```

### Page Builder Integration

#### Elementor Custom Widgets

```php
// Available custom widgets:
- Attributes Canva Hero
- Attributes Canva Testimonial
- Attributes Canva Contact Form
```

#### Beaver Builder Modules

```php
// Custom modules include:
- Hero Module
- Testimonial Module
- Advanced Contact Module
```

#### Universal Builder Support

```php
// Check active builder
$builder = attributes_canva_is_builder_page();
if ($builder === 'elementor') {
    // Elementor-specific code
}
```

## 🎨 Customization Options

### Theme Customizer Enhancements

- **Colors**: Primary color, accent color
- **Typography**: Body font, heading font
- **Layout**: Content width, sidebar options
- **Social Media**: Links and display options

### ACF Options Pages

- **Theme Options**: General settings, logos, analytics
- **Header Settings**: Navigation, CTA buttons
- **Footer Settings**: Copyright, additional menus
- **Social Media**: All social platform links

### CSS Custom Properties

```css
:root {
  --primary-color: #0073aa;
  --accent-color: #ff5722;
  --body-font: Arial, sans-serif;
  --heading-font: Arial, sans-serif;
}
```

## 🌐 Multilingual Best Practices

### String Registration

Theme strings are automatically registered for translation:

- Menu items and navigation
- Widget text and labels
- Theme option values
- Custom post type labels

### Menu Translation

- WPML: Automatic menu translation
- Polylang: Manual menu assignment per language
- All plugins: Language-specific menu locations

### Content Translation

- Pages and posts: Use plugin interfaces
- ACF fields: Automatically handled by plugins
- Theme options: Separate per language

## 🚀 Performance Optimizations

### Enhanced Features

- **Lazy Loading**: Automatic image lazy loading
- **Critical CSS**: Preloaded critical resources
- **Font Loading**: Optimized web font delivery
- **Image Optimization**: WebP support, proper sizing
- **Caching**: Builder detection caching
- **Schema Markup**: Enhanced SEO markup

### Speed Improvements

- Conditional script loading based on active features
- Minimized HTTP requests
- Optimized asset delivery
- Smart preloading of critical resources

## 🐛 Troubleshooting

### Common Issues

#### ACF Fields Not Showing

1. Ensure ACF plugin is active
2. Check `/acf-json/` directory permissions
3. Verify field group locations are correct

#### Page Builder Conflicts

1. Check if multiple page builders are active
2. Clear page builder cache
3. Verify theme compatibility mode is enabled
4. Disable conflicting plugins temporarily

#### Multilingual Issues

1. **Strings not translating**: Verify plugin string registration
2. **Menu not switching**: Check menu assignments per language
3. **ACF fields**: Ensure field group translation is enabled
4. **RTL layout**: Check language code in RTL detection function

#### Performance Issues

1. **Slow loading**: Disable unused page builders
2. **Heavy scripts**: Check for plugin conflicts
3. **Image optimization**: Verify lazy loading is working
4. **Cache conflicts**: Clear all caches after updates

### Debug Information

Add this to `wp-config.php` for debugging:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('ATTRIBUTES_CANVA_DEBUG', true);
```

## 🔧 Advanced Customization

### Custom ACF Blocks

Create additional blocks by:

1. Adding new block registration in `acf-integration.php`
2. Creating template file in `template-parts/blocks/`
3. Adding corresponding field group

#### Example: Custom Team Block

```php
// Add to acf-integration.php
acf_register_block_type([
    'name'              => 'team-member',
    'title'             => __('Team Member', 'attribute-canva'),
    'render_template'   => 'template-parts/blocks/team-member.php',
    'category'          => 'attributes-canva',
    'icon'              => 'admin-users',
    'keywords'          => ['team', 'member', 'staff'],
]);
```

### Custom Page Builder Widgets

#### Elementor Widget Example

```php
// Create: inc/elementor/widgets/custom-widget.php
class Custom_Elementor_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'custom_widget';
    }

    public function get_title() {
        return __('Custom Widget', 'attribute-canva');
    }

    // Add controls and render methods
}
```

#### Beaver Builder Module Example

```php
// Create: inc/beaver-builder/modules/custom-module/custom-module.php
class CustomBeaverModule extends FLBuilderModule {
    public function __construct() {
        parent::__construct([
            'name'          => __('Custom Module', 'attribute-canva'),
            'description'   => __('Custom functionality module', 'attribute-canva'),
            'category'      => __('Attributes Canva', 'attribute-canva'),
        ]);
    }
}

FLBuilder::register_module('CustomBeaverModule', [
    // Module configuration
]);
```

### Custom Multilingual Integration

```php
// Add support for additional multilingual plugin
function attributes_canva_custom_multilingual_support() {
    if (function_exists('custom_translate_function')) {
        add_filter('attributes_canva_translate_string', 'custom_translate_function');
    }
}
add_action('init', 'attributes_canva_custom_multilingual_support');
```

## 📊 Analytics & Tracking

### Enhanced Analytics Features

- **Google Analytics 4**: Automatic integration via ACF options
- **Reading Time Tracking**: Monitor engagement metrics
- **Social Sharing**: Track social media interactions
- **Page Builder Usage**: Monitor which builders are used

### Schema Markup Enhancements

- **Article Schema**: Automatic for blog posts
- **WebPage Schema**: For all pages
- **Organization Schema**: Site-wide organization data
- **Breadcrumb Schema**: Navigation structure
- **Language Schema**: Multilingual markup

## 🔒 Security Enhancements

### Security Features

- **Nonce Verification**: All AJAX requests protected
- **Input Sanitization**: All user inputs sanitized
- **Output Escaping**: All outputs properly escaped
- **Capability Checks**: User permission verification
- **Rate Limiting**: AJAX request throttling

### Best Practices

```php
// Always sanitize input
$safe_input = sanitize_text_field($_POST['user_input']);

// Always escape output
echo esc_html($user_data);

// Check capabilities
if (!current_user_can('edit_posts')) {
    wp_die(__('Insufficient permissions', 'attribute-canva'));
}
```

## 🚀 Deployment Guide

### Production Checklist

- [ ] Enable caching (WP Rocket, W3 Total Cache)
- [ ] Optimize images (ShortPixel, Smush)
- [ ] Configure CDN (Cloudflare, MaxCDN)
- [ ] Set up security (Wordfence, Sucuri)
- [ ] Enable SSL certificate
- [ ] Configure backup solution
- [ ] Test multilingual functionality
- [ ] Verify page builder performance
- [ ] Check ACF field display
- [ ] Test responsive design

### Performance Optimization

```php
// Add to wp-config.php for production
define('WP_CACHE', true);
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('ENFORCE_GZIP', true);
```

## 📚 API Reference

### Theme Functions

#### ACF Functions

```php
attributes_canva_get_field($field_name, $post_id, $default)
attributes_canva_get_option($field_name, $default)
attributes_canva_social_links()
attributes_canva_reading_time($post_id)
attributes_canva_display_reading_time($post_id)
attributes_canva_should_hide_title($post_id)
attributes_canva_get_page_layout($post_id)
attributes_canva_display_featured_media($post_id)
```

#### Multilingual Functions

```php
attributes_canva_translate($string, $context)
attributes_canva_language_switcher($type)
attributes_canva_is_multilingual()
attributes_canva_get_current_language()
```

#### Page Builder Functions

```php
attributes_canva_is_builder_page()
attributes_canva_builder_content_wrapper($content)
```

#### Utility Functions

```php
attributes_canva_social_sharing($post_id)
attributes_canva_breadcrumbs()
attributes_canva_preload_resources()
```

### Action Hooks

```php
do_action('attribute_canva_before_header')
do_action('attribute_canva_after_header')
do_action('attribute_canva_before_content')
do_action('attribute_canva_after_content')
do_action('attribute_canva_before_footer')
do_action('attribute_canva_after_footer')
```

### Filter Hooks

```php
apply_filters('attributes_canva_translate_string', $string)
apply_filters('attributes_canva_social_links', $links)
apply_filters('attributes_canva_reading_time', $time, $post_id)
apply_filters('attributes_canva_page_layout', $layout, $post_id)
```

## 🎯 Migration Guide

### From Basic to Enhanced Version

1. **Backup**: Full site backup before migration
2. **Upload**: Replace theme files with enhanced version
3. **Activate**: Reactivate theme to trigger setup hooks
4. **Configure**: Set up new features in admin
5. **Test**: Verify all functionality works correctly

### Plugin Dependencies

- **ACF**: Import field groups automatically
- **Page Builders**: Widgets/modules available immediately
- **Multilingual**: Strings registered on activation

## 📝 Changelog

### Version 1.1.0 (Enhanced Edition)

#### Added

- **Page Builder Integration**: Elementor, Beaver Builder, Divi support
- **ACF Integration**: Custom blocks, options pages, helper functions
- **Multilingual Support**: WPML, Polylang, qTranslate, Weglot compatibility
- **Enhanced Performance**: Optimized loading, caching, image optimization
- **Advanced Customization**: More theme options, CSS custom properties
- **SEO Improvements**: Enhanced schema markup, meta tags
- **Security Enhancements**: Better input sanitization, nonce verification

#### Improved

- **Code Organization**: Modular file structure
- **Documentation**: Comprehensive guides and examples
- **Accessibility**: Better ARIA labels, semantic markup
- **Responsive Design**: Enhanced mobile experience
- **Browser Compatibility**: Better cross-browser support

#### Fixed

- **Performance Issues**: Optimized script loading
- **Compatibility Issues**: Better plugin integration
- **Security Vulnerabilities**: Enhanced input validation

## 🤝 Contributing

### Development Setup

1. Clone repository
2. Install development dependencies
3. Follow coding standards (WordPress, PSR-12)
4. Test with multiple PHP versions (7.4+)
5. Verify compatibility with page builders
6. Test multilingual functionality

### Contribution Guidelines

- Follow WordPress coding standards
- Add proper documentation
- Include unit tests where applicable
- Test with multiple browsers and devices
- Verify accessibility compliance

## 📞 Support

### Getting Help

- **Documentation**: Check this guide first
- **Theme Support**: Contact theme author
- **Plugin Issues**: Contact plugin developers
- **Community**: WordPress forums and communities

### Reporting Issues

When reporting issues, include:

- WordPress version
- Active plugins list
- Theme version
- Page builder version (if applicable)
- Multilingual plugin version (if applicable)
- Error messages or screenshots
- Steps to reproduce

---

## 🎉 Conclusion

The enhanced Attributes Canva theme provides a comprehensive solution for modern WordPress websites with:

- **Universal Page Builder Support** for maximum flexibility
- **Advanced Custom Fields Integration** for powerful content management
- **Complete Multilingual Compatibility** for global reach
- **Performance Optimizations** for fast loading times
- **Enhanced Security** for peace of mind
- **Extensive Customization Options** for unique designs

This enhanced version maintains the simplicity and elegance of the original while adding enterprise-level features that scale with your needs.

**Happy building! 🚀**
