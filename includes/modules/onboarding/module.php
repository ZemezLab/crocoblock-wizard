<?php
namespace Crocoblock_Wizard\Modules\Onboarding;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $api = false;

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'onboarding';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-onboarding',
			CB_WIZARD_URL . 'assets/js/onboarding.js',
			array( 'cx-vue-ui' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $subpage = '' ) {

		$config['body']        = 'cbw-onboarding';
		$config['wrapper_css'] = 'onboarding-page';
		$config['has_header']  = false;
		$config['panels']      = $this->get_panels();

		return $config;

	}

	/**
	 * Returns panels list
	 *
	 * @return [type] [description]
	 */
	public function get_panels() {
		return apply_filters( 'crocoblock-wizard/onboarding/panels', array(
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H56C57.6569 1 59 2.34315 59 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M0 13V43C0 45.2 1.8 47 4 47H20.8V45H4C2.9 45 2 44.1 2 43V15H58V43C58 44.1 57.1 45 56 45H41.3V47H56C58.2 47 60 45.2 60 43V13H0Z" fill="white"/><rect x="6" y="19" width="16" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/><rect x="38" y="49.4141" width="2" height="6.14227" transform="rotate(-45 38 49.4141)" fill="white"/><path d="M42.589 55.433C41.8037 54.6477 41.8037 53.3744 42.589 52.589C43.3744 51.8037 44.6477 51.8037 45.433 52.589L48.411 55.567C49.1963 56.3523 49.1963 57.6256 48.411 58.411C47.6256 59.1963 46.3523 59.1963 45.567 58.411L42.589 55.433Z" stroke="white" stroke-width="2"/><circle cx="31" cy="42" r="11" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M31 35C30.0807 35 29.1705 35.1811 28.3212 35.5328C27.4719 35.8846 26.7003 36.4002 26.0503 37.0503C25.4002 37.7003 24.8846 38.4719 24.5328 39.3212C24.1811 40.1705 24 41.0807 24 42" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>',
				'title'      => __( 'Take a look at your site', 'crocoblock-wizard' ),
				'link'       => home_url( '/' ),
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H56C57.6569 1 59 2.34315 59 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M0 13V43C0 45.2 1.8 47 4 47H23.8V45H4C2.9 45 2 44.1 2 43V15H58V43C58 44.1 57.1 45 56 45H34.3V47H56C58.2 47 60 45.2 60 43V13H0Z" fill="white"/><path d="M16 59V54.0851L33.6709 36.4142L38.5858 41.3291L20.9149 59H16Z" stroke="white" stroke-width="2"/><path d="M18.4142 51L24.0711 56.6569L22.6569 58.0711L17 52.4142L18.4142 51Z" fill="white"/><path d="M36.9829 32.908C38.1936 31.6973 40.1565 31.6973 41.3672 32.908L42.092 33.6328C43.3027 34.8435 43.3027 36.8064 42.092 38.0171L38.5233 41.5858L33.4142 36.4767L36.9829 32.908Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><rect x="6" y="19" width="16" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/></svg>',
				'title'      => __( 'Proceed to editing pages', 'crocoblock-wizard' ),
				'link'       => admin_url( 'edit.php?post_type=page' ),
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H56C57.6569 1 59 2.34315 59 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M0 13V43C0 45.2 1.8 47 4 47H18.8V45H4C2.9 45 2 44.1 2 43V15H58V43C58 44.1 57.1 45 56 45H41.3V47H56C58.2 47 60 45.2 60 43V13H0Z" fill="white"/><path d="M41.2364 45.4328L41.151 46.0167L41.6209 46.3737L44.9069 48.8705L41.7738 54.1379L37.8195 52.6073L37.323 52.4151L36.8863 52.7198C35.8414 53.449 34.9918 53.926 34.3265 54.186L33.7851 54.3975L33.701 54.9727L33.112 59H26.888L26.299 54.9727L26.2166 54.4092L25.69 54.1926C24.7961 53.8249 23.9464 53.3412 23.1399 52.7388L22.6966 52.4076L22.1805 52.6073L18.2262 54.1379L15.0931 48.8705L18.3791 46.3737L18.849 46.0167L18.7636 45.4328C18.7232 45.1565 18.6972 44.6899 18.6972 44C18.6972 43.3101 18.7232 42.8434 18.7636 42.5672L18.849 41.9833L18.3791 41.6263L15.0931 39.1295L18.2262 33.8621L22.1805 35.3927L22.677 35.5849L23.1137 35.2802C24.1586 34.551 25.0082 34.074 25.6735 33.814L26.2149 33.6025L26.299 33.0273L26.888 29H33.112L33.701 33.0273L33.7834 33.5908L34.31 33.8074C35.2039 34.1751 36.0536 34.6588 36.8601 35.2612L37.3034 35.5924L37.8195 35.3927L41.7738 33.8621L44.9069 39.1295L41.6209 41.6263L41.151 41.9833L41.2364 42.5672C41.2768 42.8435 41.3028 43.3101 41.3028 44C41.3028 44.6899 41.2768 45.1565 41.2364 45.4328ZM41.919 54.1851C41.919 54.1851 41.9184 54.1851 41.9172 54.185L41.919 54.1851ZM33.085 59.1535C33.085 59.1535 33.0852 59.1529 33.0858 59.1519L33.085 59.1535ZM18.081 54.1851C18.0811 54.1851 18.0816 54.185 18.0827 54.185L18.081 54.1851ZM14.9939 48.6893C14.9939 48.6893 14.9942 48.6903 14.9947 48.6923C14.9941 48.6903 14.9938 48.6893 14.9939 48.6893ZM14.9939 39.3107C14.9938 39.3107 14.9941 39.3097 14.9947 39.3077C14.9942 39.3097 14.9939 39.3107 14.9939 39.3107ZM18.081 33.8149C18.081 33.8149 18.0816 33.8149 18.0828 33.815L18.081 33.8149ZM41.919 33.8149C41.9189 33.8149 41.9184 33.815 41.9173 33.815L41.919 33.8149ZM45.0061 39.3107C45.0061 39.3107 45.0058 39.3097 45.0053 39.3077C45.0059 39.3097 45.0062 39.3107 45.0061 39.3107ZM45.0061 48.6893C45.0062 48.6893 45.0059 48.6903 45.0053 48.6923C45.0058 48.6903 45.0061 48.6893 45.0061 48.6893ZM25.2259 48.6965C26.5421 49.9824 28.152 50.6338 30 50.6338C31.848 50.6338 33.4579 49.9824 34.7741 48.6965C36.0911 47.41 36.7669 45.8264 36.7669 44C36.7669 42.1736 36.0911 40.59 34.7741 39.3035C33.4579 38.0176 31.848 37.3662 30 37.3662C28.152 37.3662 26.5421 38.0176 25.2259 39.3035C23.9089 40.59 23.2331 42.1736 23.2331 44C23.2331 45.8264 23.9089 47.41 25.2259 48.6965Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><rect x="6" y="19" width="16" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/></svg>',
				'title'      => __( 'Refer to Support for assistance', 'crocoblock-wizard' ),
				'link'       => 'https://crocoblock.com/help-center/',
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="44" r="15" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M28 45C28 43.8954 28.8954 43 30 43C31.1046 43 32 43.8954 32 45V51C32 52.1046 31.1046 53 30 53C28.8954 53 28 52.1046 28 51V45Z" stroke="white" stroke-width="2"/><path d="M28 37C28 35.8954 28.8954 35 30 35C31.1046 35 32 35.8954 32 37C32 38.1046 31.1046 39 30 39C28.8954 39 28 38.1046 28 37Z" stroke="white" stroke-width="2"/><path d="M1 4C1 2.34315 2.34315 1 4 1H56C57.6569 1 59 2.34315 59 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M0 13V43C0 45.2 1.8 47 4 47H14.8V45H4C2.9 45 2 44.1 2 43V15H58V43C58 44.1 57.1 45 56 45H45.3V47H56C58.2 47 60 45.2 60 43V13H0Z" fill="white"/><rect x="6" y="19" width="16" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/></svg>',
				'title'      => __( 'Access the vast knowledge base', 'crocoblock-wizard' ),
				'link'       => 'https://crocoblock.com/knowledge-base/',
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25 5.64706C25 3.13681 27.1806 1 30 1H45C47.8194 1 50 3.13681 50 5.64706V14.3529C50 16.8632 47.8194 19 45 19H25V5.64706Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M31 8C31 7.44772 31.4477 7 32 7H43C43.5523 7 44 7.44772 44 8C44 8.55228 43.5523 9 43 9H32C31.4477 9 31 8.55228 31 8Z" fill="white"/><rect x="31" y="11" width="6" height="2" rx="1" fill="white"/><path d="M12.5868 44H20.4133C25.6384 44 30.0001 48.5581 30 54.2975V59H3V54.2975C3 48.5581 7.36172 44 12.5868 44Z" stroke="white" stroke-width="2"/><path d="M13.0912 52.0716L15.9858 44H17.0143L19.9089 52.0716L16.5 54.9358L13.0912 52.0716ZM20.0309 52.412C20.0308 52.4117 20.0307 52.4114 20.0306 52.4111L20.0309 52.4118L20.0309 52.412Z" stroke="white" stroke-width="2"/><path d="M7 30.072C7 25.1005 11.2137 21 16.5 21C21.7863 21 26 25.1005 26 30.072V34.928C26 39.8995 21.7863 44 16.5 44C11.2137 44 7 39.8996 7 34.928V30.072Z" stroke="white" stroke-width="2"/><path d="M7 30.2988V30.0523C7 25.0935 11.2118 21 16.5 21C18.069 21 19.5444 21.3647 20.8457 22.0052L20.8464 22.0056C20.878 22.0211 20.8955 22.0295 20.909 22.0359C20.9236 22.0429 20.9335 22.0477 20.9518 22.0569C21.0097 22.0863 21.0676 22.1174 21.1388 22.1557L21.1526 22.1632L21.1538 22.1639C21.169 22.172 21.1829 22.1794 21.1959 22.1864C21.238 22.2089 21.2707 22.2264 21.3046 22.2454C21.3545 22.2734 21.4045 22.3028 21.465 22.3384L21.4744 22.3439L21.4749 22.3442C21.5389 22.3818 21.5967 22.416 21.6518 22.45C21.6974 22.4781 21.7438 22.5078 21.7963 22.5414L21.7968 22.5417C21.8626 22.5839 21.9271 22.6262 21.9905 22.6691L7 30.2988ZM21.404 21.165C21.3801 21.1529 21.3559 21.1412 21.3317 21.1296C21.3168 21.1224 21.302 21.1152 21.2873 21.108C19.8517 20.4014 18.2255 20 16.5 20C10.701 20 6 24.5006 6 30.0523L21.404 21.165Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M16.5006 41C14.7951 41 13.1748 40.0854 12.1664 38.5536C11.8662 38.0977 11.9833 37.4785 12.4279 37.1707C12.8726 36.8627 13.4765 36.9829 13.7766 37.4389C14.4331 38.436 15.4259 39.0078 16.5007 39.0078C17.5756 39.0078 18.5679 38.4362 19.2231 37.4394C19.5231 36.9833 20.1267 36.8627 20.5715 37.1702C21.0164 37.4776 21.1338 38.0968 20.834 38.5529C19.8267 40.0853 18.2067 41 16.5006 41Z" fill="white"/><path d="M39.5868 47H47.4133C52.6384 47 57.0001 51.5581 57 57.2975V59H30V57.2975C30 51.5581 34.3617 47 39.5868 47Z" stroke="white" stroke-width="2"/><path d="M34 33.072C34 28.1005 38.2137 24 43.5 24C48.7863 24 53 28.1005 53 33.072V37.928C53 42.8995 48.7863 47 43.5 47C38.2137 47 34 42.8996 34 37.928V33.072Z" stroke="white" stroke-width="2"/><rect x="33" y="38" width="2" height="11" fill="white"/><path opacity="0.3" d="M39 46L34 41V49L39 46Z" fill="white"/><rect width="2" height="11" transform="matrix(-1 0 0 1 54 38)" fill="white"/><path opacity="0.3" d="M48 46L53 41V49L48 46Z" fill="white"/><path d="M34 33.2988V33.0523C34 28.0935 38.2118 24 43.5 24C45.069 24 46.5444 24.3647 47.8457 25.0052L47.8464 25.0056C47.878 25.0211 47.8955 25.0295 47.909 25.0359C47.9236 25.0429 47.9335 25.0477 47.9518 25.0569C48.0097 25.0863 48.0676 25.1174 48.1388 25.1557L48.1526 25.1632L48.1538 25.1639C48.169 25.172 48.1829 25.1794 48.1959 25.1864C48.238 25.2089 48.2707 25.2264 48.3046 25.2454C48.3545 25.2734 48.4045 25.3028 48.465 25.3384L48.4744 25.3439L48.4749 25.3442C48.5389 25.3818 48.5967 25.416 48.6518 25.45C48.6974 25.4781 48.7438 25.5078 48.7963 25.5414L48.7968 25.5417C48.8626 25.5839 48.9271 25.6262 48.9905 25.6691L34 33.2988ZM48.404 24.165C48.3801 24.1529 48.3559 24.1412 48.3317 24.1296C48.3168 24.1224 48.302 24.1152 48.2873 24.108C46.8517 23.4014 45.2255 23 43.5 23C37.701 23 33 27.5006 33 33.0523L48.404 24.165Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M43.5006 44C41.7951 44 40.1748 43.0854 39.1664 41.5536C38.8662 41.0977 38.9833 40.4785 39.4279 40.1707C39.8726 39.8627 40.4765 39.9829 40.7766 40.4389C41.4331 41.436 42.4259 42.0078 43.5007 42.0078C44.5756 42.0078 45.5679 41.4362 46.2231 40.4394C46.5231 39.9833 47.1267 39.8627 47.5715 40.1702C48.0164 40.4776 48.1338 41.0968 47.834 41.5529C46.8267 43.0853 45.2067 44 43.5006 44Z" fill="white"/></svg>',
				'title'      => __( 'Join community to stay tuned to the latest news', 'crocoblock-wizard' ),
				'link'       => 'https://www.facebook.com/groups/CrocoblockCommunity/',
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 9C8.32843 9 9 8.32843 9 7.5C9 6.67157 8.32843 6 7.5 6C6.67157 6 6 6.67157 6 7.5C6 8.32843 6.67157 9 7.5 9Z" fill="white"/><path d="M12.5 9C13.3284 9 14 8.32843 14 7.5C14 6.67157 13.3284 6 12.5 6C11.6716 6 11 6.67157 11 7.5C11 8.32843 11.6716 9 12.5 9Z" fill="white"/><path d="M17.5 9C18.3284 9 19 8.32843 19 7.5C19 6.67157 18.3284 6 17.5 6C16.6716 6 16 6.67157 16 7.5C16 8.32843 16.6716 9 17.5 9Z" fill="white"/><path d="M15 28C15 26.3431 16.3431 25 18 25H56C57.6569 25 59 26.3431 59 28V38H15V28Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M21.5 33C22.3284 33 23 32.3284 23 31.5C23 30.6716 22.3284 30 21.5 30C20.6716 30 20 30.6716 20 31.5C20 32.3284 20.6716 33 21.5 33Z" fill="white"/><path d="M26.5 33C27.3284 33 28 32.3284 28 31.5C28 30.6716 27.3284 30 26.5 30C25.6716 30 25 30.6716 25 31.5C25 32.3284 25.6716 33 26.5 33Z" fill="white"/><path d="M31.5 33C32.3284 33 33 32.3284 33 31.5C33 30.6716 32.3284 30 31.5 30C30.6716 30 30 30.6716 30 31.5C30 32.3284 30.6716 33 31.5 33Z" fill="white"/><path d="M56 60H18C15.8 60 14 58.2 14 56V37H60V56C60 58.2 58.2 60 56 60ZM16 39V56C16 57.1 16.9 58 18 58H56C57.1 58 58 57.1 58 56V39H16Z" fill="white"/><path d="M21 43H35C35.6 43 36 43.4 36 44C36 44.6 35.6 45 35 45H21C20.4 45 20 44.6 20 44C20 43.4 20.4 43 21 43Z" fill="white"/><path d="M21 47H27C27.6 47 28 47.4 28 48C28 48.6 27.6 49 27 49H21C20.4 49 20 48.6 20 48C20 47.4 20.4 47 21 47Z" fill="white"/><path d="M30.6 58H4C2.9 58 2 57.1 2 56V15H44V25.8H46V13H0V56C0 58.2 1.8 60 4 60H30.6V58Z" fill="white"/><path d="M1 4C1 2.34315 2.34315 1 4 1H42C43.6569 1 45 2.34315 45 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/></svg>',
				'title'      => __( 'Switch the current skin to another one', 'crocoblock-wizard' ),
				'link'       => Plugin::instance()->dashboard->page_url(
					Plugin::instance()->dashboard->get_initial_page()
				),
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
		) );
	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['onboarding'] = 'onboarding/main';
		return $templates;

	}

}