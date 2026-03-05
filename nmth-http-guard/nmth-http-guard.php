<?php
/*
Plugin Name: NMTH HTTP Guard
Description: Temporarily block outbound HTTP requests (except allowlisted hosts) and lower HTTP timeout to mitigate 10–15s TTFB caused by external calls. Deactivate to disable.
Version: 1.0.0
Author: Ops Helper
*/

if (!defined('ABSPATH')) { exit; }

class NMTH_HTTP_Guard {
    private $option_key = 'nmth_http_guard_recent';
    private $max_keep = 10;
    private $allowed;

    public function __construct() {
        $home_host = parse_url(home_url(), PHP_URL_HOST);
        $this->allowed = array_unique(array_filter(array_map('strtolower', array(
            $home_host, 'localhost', '127.0.0.1'
        ))));

        add_filter('pre_http_request', array($this, 'block_external'), 1, 3);
        add_filter('http_request_timeout', array($this, 'short_timeout'), 999);
        add_action('admin_bar_menu', array($this, 'admin_bar_badge'), 1000);
        // add_action('admin_notices', array($this, 'recent_notice'));
        add_action('in_admin_header', array($this, 'render_panel_on_plugins_page'));
    }

    public function short_timeout($t) {
        // Force a short timeout (2s) to avoid long waits
        $t = intval($t);
        if ($t <= 0) $t = 2;
        return min($t, 2);
    }

    public function block_external($pre, $args, $url) {
        $host = parse_url($url, PHP_URL_HOST);
        $host_l = strtolower((string)$host);
        if (!$host_l || in_array($host_l, $this->allowed, true)) {
            return $pre; // allow
        }
        $msg = 'NMTH_HTTP_Guard blocked external HTTP: ' . $host_l . ' — ' . $url;
//        if (function_exists('error_log')) { error_log($msg); }

        // Save recent
        $recent = get_transient($this->option_key);
        if (!is_array($recent)) $recent = array();
        array_unshift($recent, array('time'=>time(), 'host'=>$host_l, 'url'=>$url));
        $recent = array_slice($recent, 0, $this->max_keep);
        set_transient($this->option_key, $recent, HOUR_IN_SECONDS);

        return new WP_Error('nmth_http_guard_blocked', $msg);
    }

    public function admin_bar_badge($wp_admin_bar) {
        if (!current_user_can('manage_options')) return;
        
        $recent = get_transient($this->option_key);
        if (empty($recent)) return; // ✅ 沒封鎖紀錄就不要顯示 Admin Bar
        
        $wp_admin_bar->add_node(array(
            'id'    => 'nmth-http-guard',
            'title' => 'HTTP 外連：已封鎖',
            'href'  => admin_url('plugins.php?nmth_http_guard=1#nmth-http-guard'),
            'meta'  => array('title' => 'NMTH HTTP Guard 已啟用（停用外掛可解除）'),
        ));
    }

    public function recent_notice() {
        if (!current_user_can('manage_options')) return;

        // ✅ 只有點了 Admin Bar（帶參數）才顯示
        if (!isset($_GET['nmth_http_guard']) || $_GET['nmth_http_guard'] !== '1') return;

        // ✅ 只在外掛頁顯示（plugins.php）
        global $pagenow;
        if ($pagenow !== 'plugins.php') return;

        $recent = get_transient($this->option_key);
        if (empty($recent)) return;

        echo '<div id="nmth-http-guard" class="notice notice-warning"><p><strong>NMTH HTTP Guard</strong> 已封鎖最近的外部 HTTP 請求：</p><ol>';
        foreach ($recent as $r) {
            $t = date_i18n('Y-m-d H:i:s', intval($r['time']));
            $u = esc_html($r['url']);
            $h = esc_html($r['host']);
            echo '<li><code>' . $t . '</code> — ' . $h . ' — <span style="word-break:break-all">' . $u . '</span></li>';
        }
        echo '</ol><p>若要恢復外部連線，請在 <em>外掛</em> 停用「NMTH HTTP Guard」。</p></div>';
    }

    public function render_panel_on_plugins_page() {
        if (!current_user_can('manage_options')) return;

        // 只在 plugins.php + 點了 Admin Bar 後才顯示
        global $pagenow;
        if ($pagenow !== 'plugins.php') return;
        if (!isset($_GET['nmth_http_guard']) || $_GET['nmth_http_guard'] !== '1') return;

        $recent = get_transient($this->option_key);

        echo '<div id="nmth-http-guard" class="nmth-http-guard-panel" style="margin:12px 0;padding:12px;border:1px solid #dcdcde;background:#fff;">';

        // ✅ details：預設收合，避免版面干擾
        echo '<details>';
        echo '<summary style="cursor:pointer;"><strong>NMTH HTTP Guard</strong>（點此展開最近封鎖清單）</summary>';

        if (empty($recent)) {
            echo '<div style="margin-top:8px;">最近 1 小時內無封鎖紀錄。</div>';
            echo '</details></div>';
            return;
        }

        echo '<div style="margin-top:8px;">已封鎖最近的外部 HTTP 請求：</div>';
        echo '<ol style="margin:8px 0 8px 18px;">';
        foreach ($recent as $r) {
            $t = date_i18n('Y-m-d H:i:s', intval($r['time']));
            $u = esc_html($r['url']);
            $h = esc_html($r['host']);
            echo '<li><code>' . esc_html($t) . '</code> — ' . $h . ' — <span style="word-break:break-all">' . $u . '</span></li>';
        }
        echo '</ol>';

        echo '<div>若要恢復外部連線，請在 <em>外掛</em> 停用「NMTH HTTP Guard」。</div>';

        echo '</details>';
        echo '</div>';
    }
}

new NMTH_HTTP_Guard();
