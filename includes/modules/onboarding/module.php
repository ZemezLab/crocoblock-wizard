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

		$config['title']  = __( 'Congratulations! You’re all set!', 'crocoblock-wizard' );
		$config['cover']  = CB_WIZARD_URL . 'assets/img/cover-6.png';
		$config['body']   = 'cbw-onboarding';
		$config['panels'] = $this->get_panels();

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
				'icon'       => '<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H52C53.6569 1 55 2.34315 55 4V14H1V4Z" fill="white" stroke="#80BDDC" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="12.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="17.5" cy="7.5" r="1.5" fill="#80BDDC"/><path d="M1 14H55V40C55 41.6569 53.6569 43 52 43H4C2.34315 43 1 41.6569 1 40V14Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><rect x="35" y="45.4141" width="2" height="7" transform="rotate(-45 35 45.4141)" fill="#80BDDC"/><path d="M39.589 51.433C38.8037 50.6477 38.8037 49.3744 39.589 48.589C40.3744 47.8037 41.6477 47.8037 42.433 48.589L45.411 51.567C46.1963 52.3523 46.1963 53.6256 45.411 54.411C44.6256 55.1963 43.3523 55.1963 42.567 54.411L39.589 51.433Z" fill="white" stroke="#80BDDC" stroke-width="2"/><rect x="6" y="19" width="16" height="2" rx="1" fill="#80BDDC"/><rect x="6" y="23" width="8" height="2" rx="1" fill="#80BDDC"/><circle cx="28" cy="38" r="11" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M28 31C27.0807 31 26.1705 31.1811 25.3212 31.5328C24.4719 31.8846 23.7003 32.4002 23.0503 33.0503C22.4002 33.7003 21.8846 34.4719 21.5328 35.3212C21.1811 36.1705 21 37.0807 21 38" stroke="#80BDDC" stroke-width="2" stroke-linecap="round"/></svg>',
				'title'      => __( 'Take a look at your site', 'crocoblock-wizard' ),
				'link'       => '#',
				'link_label' => __( 'View your site', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H52C53.6569 1 55 2.34315 55 4V14H1V4Z" fill="white" stroke="#80BDDC" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="12.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="17.5" cy="7.5" r="1.5" fill="#80BDDC"/><path d="M1 14H55V40C55 41.6569 53.6569 43 52 43H4C2.34315 43 1 41.6569 1 40V14Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><rect x="6" y="19" width="16" height="2" rx="1" fill="#80BDDC"/><mask id="path-7-inside-1" fill="white"><path d="M17 49.6709L35.6709 31L42 37.3291L23.3291 56H17V49.6709Z"/></mask><path d="M17 49.6709L35.6709 31L42 37.3291L23.3291 56H17V49.6709Z" fill="white"/><path d="M17 49.6709L15.5858 48.2567L15 48.8425V49.6709H17ZM35.6709 31L37.0851 29.5858L35.6709 28.1716L34.2567 29.5858L35.6709 31ZM42 37.3291L43.4142 38.7433L44.8284 37.3291L43.4142 35.9149L42 37.3291ZM23.3291 56V58H24.1575L24.7433 57.4142L23.3291 56ZM17 56H15V58H17V56ZM18.4142 51.0851L37.0851 32.4142L34.2567 29.5858L15.5858 48.2567L18.4142 51.0851ZM34.2567 32.4142L40.5858 38.7433L43.4142 35.9149L37.0851 29.5858L34.2567 32.4142ZM40.5858 35.9149L21.9149 54.5858L24.7433 57.4142L43.4142 38.7433L40.5858 35.9149ZM23.3291 54H17V58H23.3291V54ZM19 56V49.6709H15V56H19Z" fill="#80BDDC" mask="url(#path-7-inside-1)"/><path d="M20.4142 47L26.0711 52.6569L24.6569 54.0711L19 48.4142L20.4142 47Z" fill="#80BDDC"/><mask id="path-10-inside-2" fill="white"><path d="M38.2758 28.2009C39.877 26.5997 42.4731 26.5997 44.0743 28.2009L44.7991 28.9257C46.4003 30.5269 46.4003 33.123 44.7991 34.7242L40.5233 39L34 32.4767L38.2758 28.2009Z"/></mask><path d="M38.2758 28.2009C39.877 26.5997 42.4731 26.5997 44.0743 28.2009L44.7991 28.9257C46.4003 30.5269 46.4003 33.123 44.7991 34.7242L40.5233 39L34 32.4767L38.2758 28.2009Z" fill="white"/><path d="M40.5233 39L39.1091 40.4142L40.5233 41.8284L41.9375 40.4142L40.5233 39ZM34 32.4767L32.5858 31.0625L31.1716 32.4767L32.5858 33.8909L34 32.4767ZM44.7991 28.9257L43.3849 30.3399L43.3849 30.3399L44.7991 28.9257ZM44.7991 34.7242L43.3849 33.31L44.7991 34.7242ZM44.0743 28.2009L45.4885 26.7867L45.4885 26.7867L44.0743 28.2009ZM38.2758 28.2009L36.8616 26.7867L38.2758 28.2009ZM42.6601 29.6151L43.3849 30.3399L46.2133 27.5115L45.4885 26.7867L42.6601 29.6151ZM43.3849 33.31L39.1091 37.5858L41.9375 40.4142L46.2133 36.1384L43.3849 33.31ZM41.9375 37.5858L35.4142 31.0625L32.5858 33.8909L39.1091 40.4142L41.9375 37.5858ZM35.4142 33.8909L39.69 29.6151L36.8616 26.7867L32.5858 31.0625L35.4142 33.8909ZM43.3849 30.3399C44.205 31.1601 44.205 32.4898 43.3849 33.31L46.2133 36.1384C48.5956 33.7562 48.5956 29.8938 46.2133 27.5115L43.3849 30.3399ZM45.4885 26.7867C43.1062 24.4044 39.2438 24.4044 36.8616 26.7867L39.69 29.6151C40.5102 28.795 41.8399 28.795 42.6601 29.6151L45.4885 26.7867Z" fill="#80BDDC" mask="url(#path-10-inside-2)"/><rect x="6" y="23" width="8" height="2" rx="1" fill="#80BDDC"/></svg>',
				'title'      => __( 'Proceed to editing pages', 'crocoblock-wizard' ),
				'link'       => '#',
				'link_label' => __( 'Start editing', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H52C53.6569 1 55 2.34315 55 4V14H1V4Z" fill="white" stroke="#80BDDC" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="12.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="17.5" cy="7.5" r="1.5" fill="#80BDDC"/><path d="M1 14H55V40C55 41.6569 53.6569 43 52 43H4C2.34315 43 1 41.6569 1 40V14Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><circle cx="28" cy="40" r="10" fill="#EDF6FA"/><path d="M39.2364 41.4328L39.151 42.0167L39.6209 42.3737L42.9069 44.8705L39.7738 50.1379L35.8195 48.6073L35.323 48.4151L34.8863 48.7198C33.8414 49.449 32.9918 49.926 32.3265 50.186L31.7851 50.3975L31.701 50.9727L31.112 55H24.888L24.299 50.9727L24.2166 50.4092L23.69 50.1926C22.7961 49.8249 21.9464 49.3412 21.1399 48.7388L20.6966 48.4076L20.1805 48.6073L16.2262 50.1379L13.0931 44.8705L16.3791 42.3737L16.849 42.0167L16.7636 41.4328C16.7232 41.1565 16.6972 40.6899 16.6972 40C16.6972 39.3101 16.7232 38.8434 16.7636 38.5672L16.849 37.9833L16.3791 37.6263L13.0931 35.1295L16.2262 29.8621L20.1805 31.3927L20.677 31.5849L21.1137 31.2802C22.1586 30.551 23.0082 30.074 23.6735 29.814L24.2149 29.6025L24.299 29.0273L24.888 25H31.112L31.701 29.0273L31.7834 29.5908L32.31 29.8074C33.2039 30.1751 34.0536 30.6588 34.8601 31.2612L35.3034 31.5924L35.8195 31.3927L39.7738 29.8621L42.9069 35.1295L39.6209 37.6263L39.151 37.9833L39.2364 38.5672C39.2768 38.8435 39.3028 39.3101 39.3028 40C39.3028 40.6899 39.2768 41.1565 39.2364 41.4328ZM39.919 50.1851C39.919 50.1851 39.9184 50.1851 39.9172 50.185L39.919 50.1851ZM31.085 55.1535C31.085 55.1535 31.0852 55.1529 31.0858 55.1519L31.085 55.1535ZM16.081 50.1851C16.0811 50.1851 16.0816 50.185 16.0827 50.185L16.081 50.1851ZM12.9939 44.6893C12.9939 44.6893 12.9942 44.6903 12.9947 44.6923C12.9941 44.6903 12.9938 44.6893 12.9939 44.6893ZM12.9939 35.3107C12.9938 35.3107 12.9941 35.3097 12.9947 35.3077C12.9942 35.3097 12.9939 35.3107 12.9939 35.3107ZM16.081 29.8149C16.081 29.8149 16.0816 29.8149 16.0828 29.815L16.081 29.8149ZM39.919 29.8149C39.9189 29.8149 39.9184 29.815 39.9173 29.815L39.919 29.8149ZM43.0061 35.3107C43.0061 35.3107 43.0058 35.3097 43.0053 35.3077C43.0059 35.3097 43.0062 35.3107 43.0061 35.3107ZM43.0061 44.6893C43.0062 44.6893 43.0059 44.6903 43.0053 44.6923C43.0058 44.6903 43.0061 44.6893 43.0061 44.6893ZM23.2259 44.6965C24.5421 45.9824 26.152 46.6338 28 46.6338C29.848 46.6338 31.4579 45.9824 32.7741 44.6965C34.0911 43.41 34.7669 41.8264 34.7669 40C34.7669 38.1736 34.0911 36.59 32.7741 35.3035C31.4579 34.0176 29.848 33.3662 28 33.3662C26.152 33.3662 24.5421 34.0176 23.2259 35.3035C21.9089 36.59 21.2331 38.1736 21.2331 40C21.2331 41.8264 21.9089 43.41 23.2259 44.6965Z" fill="white" stroke="#80BDDC" stroke-width="2"/></svg>',
				'title'      => __( 'Get more info from documentation', 'crocoblock-wizard' ),
				'link'       => '#',
				'link_label' => __( 'Check documentation', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H52C53.6569 1 55 2.34315 55 4V14H1V4Z" fill="white" stroke="#80BDDC" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="12.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="17.5" cy="7.5" r="1.5" fill="#80BDDC"/><path d="M1 14H55V40C55 41.6569 53.6569 43 52 43H4C2.34315 43 1 41.6569 1 40V14Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><circle cx="28" cy="40" r="15" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M26 41C26 39.8954 26.8954 39 28 39C29.1046 39 30 39.8954 30 41V47C30 48.1046 29.1046 49 28 49C26.8954 49 26 48.1046 26 47V41Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M26 33C26 31.8954 26.8954 31 28 31C29.1046 31 30 31.8954 30 33C30 34.1046 29.1046 35 28 35C26.8954 35 26 34.1046 26 33Z" fill="white" stroke="#80BDDC" stroke-width="2"/><rect x="6" y="19" width="16" height="2" rx="1" fill="#80BDDC"/><rect x="6" y="23" width="8" height="2" rx="1" fill="#80BDDC"/></svg>',
				'title'      => __( 'Access the vast knowledge base', 'crocoblock-wizard' ),
				'link'       => '#',
				'link_label' => __( 'Knowledge base', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23 5.64706C23 3.13681 25.1806 1 28 1H37C39.8194 1 42 3.13681 42 5.64706V10.3529C42 12.8632 39.8194 15 37 15H23V5.64706Z" fill="white" stroke="#80BDDC" stroke-width="2"/><rect x="27" y="5" width="11" height="2" rx="1" fill="#80BDDC"/><rect x="27" y="9" width="6" height="2" rx="1" fill="#80BDDC"/><rect x="32" y="31" width="19" height="16" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M10.5868 40H18.4133C23.6384 40 28.0001 44.5581 28 50.2975V55H1V50.2975C1 44.5581 5.36172 40 10.5868 40Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><path d="M11.0522 47.5437L14.0185 38H14.9816L17.9478 47.5437L14.5 50.8864L11.0522 47.5437ZM14.7069 51.087L14.7066 51.0867L14.7069 51.087Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M5 26.072C5 21.1005 9.2137 17 14.5 17C19.7863 17 24 21.1005 24 26.072V30.928C24 35.8995 19.7863 40 14.5 40C9.21369 40 5 35.8996 5 30.928V26.072Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><path d="M5 26.2988V26.0523C5 21.0935 9.21182 17 14.5 17C16.069 17 17.5444 17.3647 18.8457 18.0052L18.8464 18.0056C18.878 18.0211 18.8955 18.0295 18.909 18.0359C18.9236 18.0429 18.9335 18.0477 18.9518 18.0569C19.0097 18.0863 19.0676 18.1174 19.1388 18.1557L19.1526 18.1632L19.1538 18.1639C19.169 18.172 19.1829 18.1794 19.1959 18.1864C19.238 18.2089 19.2707 18.2264 19.3046 18.2454C19.3545 18.2734 19.4045 18.3028 19.465 18.3384L19.4744 18.3439L19.4749 18.3442C19.5389 18.3818 19.5967 18.416 19.6518 18.45C19.6974 18.4781 19.7438 18.5078 19.7963 18.5414L19.7968 18.5417C19.8626 18.5839 19.9271 18.6262 19.9905 18.6691L5 26.2988ZM19.404 17.165C19.3801 17.1529 19.3559 17.1412 19.3317 17.1296C19.3168 17.1224 19.302 17.1152 19.2873 17.108C17.8517 16.4014 16.2255 16 14.5 16C8.70104 16 4 20.5006 4 26.0523L19.404 17.165Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M14.5006 37C12.7951 37 11.1748 36.0854 10.1664 34.5536C9.86621 34.0977 9.98331 33.4785 10.4279 33.1707C10.8726 32.8627 11.4765 32.9829 11.7766 33.4389C12.4331 34.436 13.4259 35.0078 14.5007 35.0078C15.5756 35.0078 16.5679 34.4362 17.2231 33.4394C17.5231 32.9833 18.1267 32.8627 18.5715 33.1702C19.0164 33.4776 19.1338 34.0968 18.834 34.5529C17.8267 36.0853 16.2067 37 14.5006 37Z" fill="#80BDDC"/><path d="M37.5868 43H45.4133C50.6384 43 55.0001 47.5581 55 53.2975V55H28V53.2975C28 47.5581 32.3617 43 37.5868 43Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><path d="M32 29.072C32 24.1005 36.2137 20 41.5 20C46.7863 20 51 24.1005 51 29.072V33.928C51 38.8995 46.7863 43 41.5 43C36.2137 43 32 38.8996 32 33.928V29.072Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><path d="M32 29.2988V29.0523C32 24.0935 36.2118 20 41.5 20C43.069 20 44.5444 20.3647 45.8457 21.0052L45.8464 21.0056C45.878 21.0211 45.8955 21.0295 45.909 21.0359C45.9236 21.0429 45.9335 21.0477 45.9518 21.0569C46.0097 21.0863 46.0676 21.1174 46.1388 21.1557L46.1526 21.1632L46.1538 21.1639C46.169 21.172 46.1829 21.1794 46.1959 21.1864C46.238 21.2089 46.2707 21.2264 46.3046 21.2454C46.3545 21.2734 46.4045 21.3028 46.465 21.3384L46.4744 21.3439L46.4749 21.3442C46.5389 21.3818 46.5967 21.416 46.6518 21.45C46.6974 21.4781 46.7438 21.5078 46.7963 21.5414L46.7968 21.5417C46.8626 21.5839 46.9271 21.6262 46.9905 21.6691L32 29.2988ZM46.404 20.165C46.3801 20.1529 46.3559 20.1412 46.3317 20.1296C46.3168 20.1224 46.302 20.1152 46.2873 20.108C44.8517 19.4014 43.2255 19 41.5 19C35.701 19 31 23.5006 31 29.0523L46.404 20.165Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M41.5006 40C39.7951 40 38.1748 39.0854 37.1664 37.5536C36.8662 37.0977 36.9833 36.4785 37.4279 36.1707C37.8726 35.8627 38.4765 35.9829 38.7766 36.4389C39.4331 37.436 40.4259 38.0078 41.5007 38.0078C42.5756 38.0078 43.5679 37.4362 44.2231 36.4394C44.5231 35.9833 45.1267 35.8627 45.5715 36.1702C46.0164 36.4776 46.1338 37.0968 45.834 37.5529C44.8267 39.0853 43.2067 40 41.5006 40Z" fill="#80BDDC"/></svg>',
				'title'      => __( 'Join community to stay tuned to the latest news', 'crocoblock-wizard' ),
				'link'       => '#',
				'link_label' => __( 'Community', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H36C37.6569 1 39 2.34315 39 4V14H1V4Z" fill="white" stroke="#80BDDC" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="12.5" cy="7.5" r="1.5" fill="#80BDDC"/><circle cx="17.5" cy="7.5" r="1.5" fill="#80BDDC"/><path d="M1 14H39V33C39 34.6569 37.6569 36 36 36H4C2.34315 36 1 34.6569 1 33V14Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><path d="M17 23C17 21.3431 18.3431 20 20 20H52C53.6569 20 55 21.3431 55 23V33H17V23Z" fill="white" stroke="#80BDDC" stroke-width="2"/><circle cx="23.5" cy="26.5" r="1.5" fill="#80BDDC"/><circle cx="28.5" cy="26.5" r="1.5" fill="#80BDDC"/><circle cx="33.5" cy="26.5" r="1.5" fill="#80BDDC"/><path d="M17 33H55V52C55 53.6569 53.6569 55 52 55H20C18.3431 55 17 53.6569 17 52V33Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><rect x="22" y="38" width="16" height="2" rx="1" fill="#80BDDC"/><rect x="22" y="42" width="8" height="2" rx="1" fill="#80BDDC"/></svg>',
				'title'      => __( 'Switch the current skin to another one', 'crocoblock-wizard' ),
				'link'       => '#',
				'link_label' => __( 'Select skin', 'crocoblock-wizard' ),
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