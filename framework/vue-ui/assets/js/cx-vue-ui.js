/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);


const checkConditions = {
	methods: {
		isVisible() {

			if (!this.conditions.length) {
				return true;
			} else {

				let conditionsMet = [];
				let operator = 'AND';
				let conditionsLength = this.conditions.length;

				for (var i = 0; i < this.conditions.length; i++) {

					if (this.conditions[i].operator) {
						operator = this.conditions[i].operator;
						conditionsLength--;
						continue;
					}

					switch (this.conditions[i].compare) {

						case 'equal':

							if (this.conditions[i].input === this.conditions[i].value) {
								conditionsMet.push(this.conditions[i].value);
							}

							break;

						case 'not_equal':

							if (this.conditions[i].input !== this.conditions[i].value) {
								conditionsMet.push(this.conditions[i].value);
							}

							break;

						case 'in':

							if (Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(this.conditions[i].input, this.conditions[i].value)) {
								conditionsMet.push(this.conditions[i].value);
							}

							break;

						case 'not_in':

							if (!Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(this.conditions[i].input, this.conditions[i].value)) {
								conditionsMet.push(this.conditions[i].value);
							}

							break;

						case 'contains':

							if (Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(this.conditions[i].value, this.conditions[i].input)) {
								conditionsMet.push(this.conditions[i].value);
							}

							break;

						case 'not_contains':

							if (!Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(this.conditions[i].value, this.conditions[i].input)) {
								conditionsMet.push(this.conditions[i].value);
							}

							break;

					}
				};

				switch (operator) {
					case 'AND':
						return conditionsMet.length === conditionsLength;
					case 'OR':
						if (conditionsMet.length) {
							return true;
						} else {
							return false;
						}
				}
			}
		}
	}
};
/* harmony export (immutable) */ __webpack_exports__["a"] = checkConditions;


/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["b"] = oneOf;
/* harmony export (immutable) */ __webpack_exports__["a"] = arraysEqual;
function oneOf(value, validList) {

	for (let i = 0; i < validList.length; i++) {
		if (value == validList[i]) {
			return true;
		}
	}

	return false;
}

function arraysEqual(arr1, arr2) {

	if (arr1.length !== arr2.length) {
		return false;
	}

	for (var i = arr1.length; i--;) {
		if (arr1[i] !== arr2[i]) {
			return false;
		}
	}

	return true;
}

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

(function (global, factory) {
	 true ? factory(exports) :
	typeof define === 'function' && define.amd ? define(['exports'], factory) :
	(factory((global.VueSlicksort = {})));
}(this, (function (exports) { 'use strict';

// Export Sortable Element Component Mixin
var ElementMixin = {
  inject: ['manager'],
  props: {
    index: {
      type: Number,
      required: true
    },
    collection: {
      type: [String, Number],
      default: 'default'
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },

  mounted: function mounted() {
    var _$props = this.$props,
        collection = _$props.collection,
        disabled = _$props.disabled,
        index = _$props.index;


    if (!disabled) {
      this.setDraggable(collection, index);
    }
  },


  watch: {
    index: function index(newIndex) {
      if (this.$el && this.$el.sortableInfo) {
        this.$el.sortableInfo.index = newIndex;
      }
    },
    disabled: function disabled(isDisabled) {
      if (isDisabled) {
        this.removeDraggable(this.collection);
      } else {
        this.setDraggable(this.collection, this.index);
      }
    },
    collection: function collection(newCollection, oldCollection) {
      this.removeDraggable(oldCollection);
      this.setDraggable(newCollection, this.index);
    }
  },

  beforeDestroy: function beforeDestroy() {
    var collection = this.collection,
        disabled = this.disabled;


    if (!disabled) this.removeDraggable(collection);
  },

  methods: {
    setDraggable: function setDraggable(collection, index) {
      var node = this.$el;

      node.sortableInfo = {
        index: index,
        collection: collection,
        manager: this.manager
      };

      this.ref = { node: node };
      this.manager.add(collection, this.ref);
    },
    removeDraggable: function removeDraggable(collection) {
      this.manager.remove(collection, this.ref);
    }
  }
};

var classCallCheck = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

var createClass = function () {
  function defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  return function (Constructor, protoProps, staticProps) {
    if (protoProps) defineProperties(Constructor.prototype, protoProps);
    if (staticProps) defineProperties(Constructor, staticProps);
    return Constructor;
  };
}();



























var slicedToArray = function () {
  function sliceIterator(arr, i) {
    var _arr = [];
    var _n = true;
    var _d = false;
    var _e = undefined;

    try {
      for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
        _arr.push(_s.value);

        if (i && _arr.length === i) break;
      }
    } catch (err) {
      _d = true;
      _e = err;
    } finally {
      try {
        if (!_n && _i["return"]) _i["return"]();
      } finally {
        if (_d) throw _e;
      }
    }

    return _arr;
  }

  return function (arr, i) {
    if (Array.isArray(arr)) {
      return arr;
    } else if (Symbol.iterator in Object(arr)) {
      return sliceIterator(arr, i);
    } else {
      throw new TypeError("Invalid attempt to destructure non-iterable instance");
    }
  };
}();













var toConsumableArray = function (arr) {
  if (Array.isArray(arr)) {
    for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) arr2[i] = arr[i];

    return arr2;
  } else {
    return Array.from(arr);
  }
};

var Manager = function () {
  function Manager() {
    classCallCheck(this, Manager);

    this.refs = {};
  }

  createClass(Manager, [{
    key: "add",
    value: function add(collection, ref) {
      if (!this.refs[collection]) {
        this.refs[collection] = [];
      }

      this.refs[collection].push(ref);
    }
  }, {
    key: "remove",
    value: function remove(collection, ref) {
      var index = this.getIndex(collection, ref);

      if (index !== -1) {
        this.refs[collection].splice(index, 1);
      }
    }
  }, {
    key: "isActive",
    value: function isActive() {
      return this.active;
    }
  }, {
    key: "getActive",
    value: function getActive() {
      var _this = this;

      return this.refs[this.active.collection].find(function (_ref) {
        var node = _ref.node;
        return node.sortableInfo.index == _this.active.index;
      });
    }
  }, {
    key: "getIndex",
    value: function getIndex(collection, ref) {
      return this.refs[collection].indexOf(ref);
    }
  }, {
    key: "getOrderedRefs",
    value: function getOrderedRefs() {
      var collection = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.active.collection;

      return this.refs[collection].sort(function (a, b) {
        return a.node.sortableInfo.index - b.node.sortableInfo.index;
      });
    }
  }]);
  return Manager;
}();

function arrayMove(arr, previousIndex, newIndex) {
  var array = arr.slice(0);
  if (newIndex >= array.length) {
    var k = newIndex - array.length;
    while (k-- + 1) {
      array.push(undefined);
    }
  }
  array.splice(newIndex, 0, array.splice(previousIndex, 1)[0]);
  return array;
}

var events = {
  start: ['touchstart', 'mousedown'],
  move: ['touchmove', 'mousemove'],
  end: ['touchend', 'touchcancel', 'mouseup']
};

var vendorPrefix = function () {
  if (typeof window === 'undefined' || typeof document === 'undefined') return ''; // server environment
  // fix for:
  //    https://bugzilla.mozilla.org/show_bug.cgi?id=548397
  //    window.getComputedStyle() returns null inside an iframe with display: none
  // in this case return an array with a fake mozilla style in it.
  var styles = window.getComputedStyle(document.documentElement, '') || ['-moz-hidden-iframe'];
  var pre = (Array.prototype.slice.call(styles).join('').match(/-(moz|webkit|ms)-/) || styles.OLink === '' && ['', 'o'])[1];

  switch (pre) {
    case 'ms':
      return 'ms';
    default:
      return pre && pre.length ? pre[0].toUpperCase() + pre.substr(1) : '';
  }
}();

function closest(el, fn) {
  while (el) {
    if (fn(el)) return el;
    el = el.parentNode;
  }
}

function limit(min, max, value) {
  if (value < min) {
    return min;
  }
  if (value > max) {
    return max;
  }
  return value;
}

function getCSSPixelValue(stringValue) {
  if (stringValue.substr(-2) === 'px') {
    return parseFloat(stringValue);
  }
  return 0;
}

function getElementMargin(element) {
  var style = window.getComputedStyle(element);

  return {
    top: getCSSPixelValue(style.marginTop),
    right: getCSSPixelValue(style.marginRight),
    bottom: getCSSPixelValue(style.marginBottom),
    left: getCSSPixelValue(style.marginLeft)
  };
}

// Export Sortable Container Component Mixin
var ContainerMixin = {
  data: function data() {
    return {
      sorting: false,
      sortingIndex: null,
      manager: new Manager(),
      events: {
        start: this.handleStart,
        move: this.handleMove,
        end: this.handleEnd
      }
    };
  },


  props: {
    value: { type: Array, required: true },
    axis: { type: String, default: 'y' }, // 'x', 'y', 'xy'
    distance: { type: Number, default: 0 },
    pressDelay: { type: Number, default: 0 },
    pressThreshold: { type: Number, default: 5 },
    useDragHandle: { type: Boolean, default: false },
    useWindowAsScrollContainer: { type: Boolean, default: false },
    hideSortableGhost: { type: Boolean, default: true },
    lockToContainerEdges: { type: Boolean, default: false },
    lockOffset: { type: [String, Number, Array], default: '50%' },
    transitionDuration: { type: Number, default: 300 },
    appendTo: { type: String, default: 'body' },
    draggedSettlingDuration: { type: Number, default: null },
    lockAxis: String,
    helperClass: String,
    contentWindow: Object,
    shouldCancelStart: {
      type: Function,
      default: function _default(e) {
        // Cancel sorting if the event target is an `input`, `textarea`, `select` or `option`
        var disabledElements = ['input', 'textarea', 'select', 'option', 'button'];
        return disabledElements.indexOf(e.target.tagName.toLowerCase()) !== -1;
      }
    },
    getHelperDimensions: {
      type: Function,
      default: function _default(_ref) {
        var node = _ref.node;
        return {
          width: node.offsetWidth,
          height: node.offsetHeight
        };
      }
    }
  },

  provide: function provide() {
    return {
      manager: this.manager
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.container = this.$el;
    this.document = this.container.ownerDocument || document;
    this._window = this.contentWindow || window;
    this.scrollContainer = this.useWindowAsScrollContainer ? this.document.body : this.container;

    var _loop = function _loop(key) {
      if (_this.events.hasOwnProperty(key)) {
        events[key].forEach(function (eventName) {
          return _this.container.addEventListener(eventName, _this.events[key], false);
        });
      }
    };

    for (var key in this.events) {
      _loop(key);
    }
  },
  beforeDestroy: function beforeDestroy() {
    var _this2 = this;

    var _loop2 = function _loop2(key) {
      if (_this2.events.hasOwnProperty(key)) {
        events[key].forEach(function (eventName) {
          return _this2.container.removeEventListener(eventName, _this2.events[key]);
        });
      }
    };

    for (var key in this.events) {
      _loop2(key);
    }
  },


  methods: {
    handleStart: function handleStart(e) {
      var _this3 = this;

      var _$props = this.$props,
          distance = _$props.distance,
          shouldCancelStart = _$props.shouldCancelStart;


      if (e.button === 2 || shouldCancelStart(e)) {
        return false;
      }

      this._touched = true;
      this._pos = {
        x: e.pageX,
        y: e.pageY
      };

      var node = closest(e.target, function (el) {
        return el.sortableInfo != null;
      });

      if (node && node.sortableInfo && this.nodeIsChild(node) && !this.sorting) {
        var useDragHandle = this.$props.useDragHandle;
        var _node$sortableInfo = node.sortableInfo,
            index = _node$sortableInfo.index,
            collection = _node$sortableInfo.collection;


        if (useDragHandle && !closest(e.target, function (el) {
          return el.sortableHandle != null;
        })) return;

        this.manager.active = { index: index, collection: collection };

        /*
        * Fixes a bug in Firefox where the :active state of anchor tags
        * prevent subsequent 'mousemove' events from being fired
        * (see https://github.com/clauderic/react-sortable-hoc/issues/118)
        */
        if (e.target.tagName.toLowerCase() === 'a') {
          e.preventDefault();
        }

        if (!distance) {
          if (this.$props.pressDelay === 0) {
            this.handlePress(e);
          } else {
            this.pressTimer = setTimeout(function () {
              return _this3.handlePress(e);
            }, this.$props.pressDelay);
          }
        }
      }
    },
    nodeIsChild: function nodeIsChild(node) {
      return node.sortableInfo.manager === this.manager;
    },
    handleMove: function handleMove(e) {
      var _$props2 = this.$props,
          distance = _$props2.distance,
          pressThreshold = _$props2.pressThreshold;


      if (!this.sorting && this._touched) {
        this._delta = {
          x: this._pos.x - e.pageX,
          y: this._pos.y - e.pageY
        };
        var delta = Math.abs(this._delta.x) + Math.abs(this._delta.y);

        if (!distance && (!pressThreshold || pressThreshold && delta >= pressThreshold)) {
          clearTimeout(this.cancelTimer);
          this.cancelTimer = setTimeout(this.cancel, 0);
        } else if (distance && delta >= distance && this.manager.isActive()) {
          this.handlePress(e);
        }
      }
    },
    handleEnd: function handleEnd() {
      var distance = this.$props.distance;


      this._touched = false;

      if (!distance) {
        this.cancel();
      }
    },
    cancel: function cancel() {
      if (!this.sorting) {
        clearTimeout(this.pressTimer);
        this.manager.active = null;
      }
    },
    handlePress: function handlePress(e) {
      var _this4 = this;

      var active = this.manager.getActive();

      if (active) {
        var _$props3 = this.$props,
            axis = _$props3.axis,
            getHelperDimensions = _$props3.getHelperDimensions,
            helperClass = _$props3.helperClass,
            hideSortableGhost = _$props3.hideSortableGhost,
            useWindowAsScrollContainer = _$props3.useWindowAsScrollContainer,
            appendTo = _$props3.appendTo;
        var node = active.node,
            collection = active.collection;
        var index = node.sortableInfo.index;

        var margin = getElementMargin(node);

        var containerBoundingRect = this.container.getBoundingClientRect();
        var dimensions = getHelperDimensions({ index: index, node: node, collection: collection });

        this.node = node;
        this.margin = margin;
        this.width = dimensions.width;
        this.height = dimensions.height;
        this.marginOffset = {
          x: this.margin.left + this.margin.right,
          y: Math.max(this.margin.top, this.margin.bottom)
        };
        this.boundingClientRect = node.getBoundingClientRect();
        this.containerBoundingRect = containerBoundingRect;
        this.index = index;
        this.newIndex = index;

        this._axis = {
          x: axis.indexOf('x') >= 0,
          y: axis.indexOf('y') >= 0
        };
        this.offsetEdge = this.getEdgeOffset(node);
        this.initialOffset = this.getOffset(e);
        this.initialScroll = {
          top: this.scrollContainer.scrollTop,
          left: this.scrollContainer.scrollLeft
        };

        this.initialWindowScroll = {
          top: window.pageYOffset,
          left: window.pageXOffset
        };

        var fields = node.querySelectorAll('input, textarea, select');
        var clonedNode = node.cloneNode(true);
        var clonedFields = [].concat(toConsumableArray(clonedNode.querySelectorAll('input, textarea, select'))); // Convert NodeList to Array

        clonedFields.forEach(function (field, index) {
          if (field.type !== 'file' && fields[index]) {
            field.value = fields[index].value;
          }
        });

        this.helper = this.document.querySelector(appendTo).appendChild(clonedNode);

        this.helper.style.position = 'fixed';
        this.helper.style.top = this.boundingClientRect.top - margin.top + 'px';
        this.helper.style.left = this.boundingClientRect.left - margin.left + 'px';
        this.helper.style.width = this.width + 'px';
        this.helper.style.height = this.height + 'px';
        this.helper.style.boxSizing = 'border-box';
        this.helper.style.pointerEvents = 'none';

        if (hideSortableGhost) {
          this.sortableGhost = node;
          node.style.visibility = 'hidden';
          node.style.opacity = 0;
        }

        this.translate = {};
        this.minTranslate = {};
        this.maxTranslate = {};
        if (this._axis.x) {
          this.minTranslate.x = (useWindowAsScrollContainer ? 0 : containerBoundingRect.left) - this.boundingClientRect.left - this.width / 2;
          this.maxTranslate.x = (useWindowAsScrollContainer ? this._window.innerWidth : containerBoundingRect.left + containerBoundingRect.width) - this.boundingClientRect.left - this.width / 2;
        }
        if (this._axis.y) {
          this.minTranslate.y = (useWindowAsScrollContainer ? 0 : containerBoundingRect.top) - this.boundingClientRect.top - this.height / 2;
          this.maxTranslate.y = (useWindowAsScrollContainer ? this._window.innerHeight : containerBoundingRect.top + containerBoundingRect.height) - this.boundingClientRect.top - this.height / 2;
        }

        if (helperClass) {
          var _helper$classList;

          (_helper$classList = this.helper.classList).add.apply(_helper$classList, toConsumableArray(helperClass.split(' ')));
        }

        this.listenerNode = e.touches ? node : this._window;
        events.move.forEach(function (eventName) {
          return _this4.listenerNode.addEventListener(eventName, _this4.handleSortMove, false);
        });
        events.end.forEach(function (eventName) {
          return _this4.listenerNode.addEventListener(eventName, _this4.handleSortEnd, false);
        });

        this.sorting = true;
        this.sortingIndex = index;

        this.$emit('sort-start', { event: e, node: node, index: index, collection: collection });
      }
    },
    handleSortMove: function handleSortMove(e) {
      e.preventDefault(); // Prevent scrolling on mobile

      this.updatePosition(e);
      this.animateNodes();
      this.autoscroll();

      this.$emit('sort-move', { event: e });
    },
    handleSortEnd: function handleSortEnd(e) {
      var _this5 = this;

      var collection = this.manager.active.collection;

      // Remove the event listeners if the node is still in the DOM

      if (this.listenerNode) {
        events.move.forEach(function (eventName) {
          return _this5.listenerNode.removeEventListener(eventName, _this5.handleSortMove);
        });
        events.end.forEach(function (eventName) {
          return _this5.listenerNode.removeEventListener(eventName, _this5.handleSortEnd);
        });
      }

      var nodes = this.manager.refs[collection];

      var onEnd = function onEnd() {
        // Remove the helper from the DOM
        _this5.helper.parentNode.removeChild(_this5.helper);

        if (_this5.hideSortableGhost && _this5.sortableGhost) {
          _this5.sortableGhost.style.visibility = '';
          _this5.sortableGhost.style.opacity = '';
        }

        for (var i = 0, len = nodes.length; i < len; i++) {
          var node = nodes[i];
          var el = node.node;

          // Clear the cached offsetTop / offsetLeft value
          node.edgeOffset = null;

          // Remove the transforms / transitions
          el.style[vendorPrefix + 'Transform'] = '';
          el.style[vendorPrefix + 'TransitionDuration'] = '';
        }

        // Stop autoscroll
        clearInterval(_this5.autoscrollInterval);
        _this5.autoscrollInterval = null;

        // Update state
        _this5.manager.active = null;

        _this5.sorting = false;
        _this5.sortingIndex = null;

        _this5.$emit('sort-end', {
          event: e,
          oldIndex: _this5.index,
          newIndex: _this5.newIndex,
          collection: collection
        });
        _this5.$emit('input', arrayMove(_this5.value, _this5.index, _this5.newIndex));

        _this5._touched = false;
      };

      if (this.$props.transitionDuration || this.$props.draggedSettlingDuration) {
        this.transitionHelperIntoPlace(nodes).then(function () {
          return onEnd();
        });
      } else {
        onEnd();
      }
    },
    transitionHelperIntoPlace: function transitionHelperIntoPlace(nodes) {
      var _this6 = this;

      if (this.$props.draggedSettlingDuration === 0) {
        return Promise.resolve();
      }

      var deltaScroll = {
        left: this.scrollContainer.scrollLeft - this.initialScroll.left,
        top: this.scrollContainer.scrollTop - this.initialScroll.top
      };
      var indexNode = nodes[this.index].node;
      var newIndexNode = nodes[this.newIndex].node;

      var targetX = -deltaScroll.left;
      if (this.translate && this.translate.x > 0) {
        // Diff against right edge when moving to the right
        targetX += newIndexNode.offsetLeft + newIndexNode.offsetWidth - (indexNode.offsetLeft + indexNode.offsetWidth);
      } else {
        targetX += newIndexNode.offsetLeft - indexNode.offsetLeft;
      }

      var targetY = -deltaScroll.top;
      if (this.translate && this.translate.y > 0) {
        // Diff against the bottom edge when moving down
        targetY += newIndexNode.offsetTop + newIndexNode.offsetHeight - (indexNode.offsetTop + indexNode.offsetHeight);
      } else {
        targetY += newIndexNode.offsetTop - indexNode.offsetTop;
      }

      var duration = this.$props.draggedSettlingDuration !== null ? this.$props.draggedSettlingDuration : this.$props.transitionDuration;

      this.helper.style[vendorPrefix + 'Transform'] = 'translate3d(' + targetX + 'px,' + targetY + 'px, 0)';
      this.helper.style[vendorPrefix + 'TransitionDuration'] = duration + 'ms';

      return new Promise(function (resolve) {
        // Register an event handler to clean up styles when the transition
        // finishes.
        var cleanup = function cleanup(event) {
          if (!event || event.propertyName === 'transform') {
            clearTimeout(cleanupTimer);
            _this6.helper.style[vendorPrefix + 'Transform'] = '';
            _this6.helper.style[vendorPrefix + 'TransitionDuration'] = '';
            resolve();
          }
        };
        // Force cleanup in case 'transitionend' never fires
        var cleanupTimer = setTimeout(cleanup, duration + 10);
        _this6.helper.addEventListener('transitionend', cleanup, false);
      });
    },
    getEdgeOffset: function getEdgeOffset(node) {
      var offset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : { top: 0, left: 0 };

      // Get the actual offsetTop / offsetLeft value, no matter how deep the node is nested
      if (node) {
        var nodeOffset = {
          top: offset.top + node.offsetTop,
          left: offset.left + node.offsetLeft
        };
        if (node.parentNode !== this.container) {
          return this.getEdgeOffset(node.parentNode, nodeOffset);
        } else {
          return nodeOffset;
        }
      }
    },
    getOffset: function getOffset(e) {
      return {
        x: e.touches ? e.touches[0].pageX : e.pageX,
        y: e.touches ? e.touches[0].pageY : e.pageY
      };
    },
    getLockPixelOffsets: function getLockPixelOffsets() {
      var lockOffset = this.$props.lockOffset;


      if (!Array.isArray(this.lockOffset)) {
        lockOffset = [lockOffset, lockOffset];
      }

      if (lockOffset.length !== 2) {
        throw new Error('lockOffset prop of SortableContainer should be a single value or an array of exactly two values. Given ' + lockOffset);
      }

      var _lockOffset = lockOffset,
          _lockOffset2 = slicedToArray(_lockOffset, 2),
          minLockOffset = _lockOffset2[0],
          maxLockOffset = _lockOffset2[1];

      return [this.getLockPixelOffset(minLockOffset), this.getLockPixelOffset(maxLockOffset)];
    },
    getLockPixelOffset: function getLockPixelOffset(lockOffset) {
      var offsetX = lockOffset;
      var offsetY = lockOffset;
      var unit = 'px';

      if (typeof lockOffset === 'string') {
        var match = /^[+-]?\d*(?:\.\d*)?(px|%)$/.exec(lockOffset);

        if (match === null) {
          throw new Error('lockOffset value should be a number or a string of a number followed by "px" or "%". Given ' + lockOffset);
        }

        offsetX = offsetY = parseFloat(lockOffset);
        unit = match[1];
      }

      if (!isFinite(offsetX) || !isFinite(offsetY)) {
        throw new Error('lockOffset value should be a finite. Given ' + lockOffset);
      }

      if (unit === '%') {
        offsetX = offsetX * this.width / 100;
        offsetY = offsetY * this.height / 100;
      }

      return {
        x: offsetX,
        y: offsetY
      };
    },
    updatePosition: function updatePosition(e) {
      var _$props4 = this.$props,
          lockAxis = _$props4.lockAxis,
          lockToContainerEdges = _$props4.lockToContainerEdges;


      var offset = this.getOffset(e);
      var translate = {
        x: offset.x - this.initialOffset.x,
        y: offset.y - this.initialOffset.y
      };
      // Adjust for window scroll
      translate.y -= window.pageYOffset - this.initialWindowScroll.top;
      translate.x -= window.pageXOffset - this.initialWindowScroll.left;

      this.translate = translate;

      if (lockToContainerEdges) {
        var _getLockPixelOffsets = this.getLockPixelOffsets(),
            _getLockPixelOffsets2 = slicedToArray(_getLockPixelOffsets, 2),
            minLockOffset = _getLockPixelOffsets2[0],
            maxLockOffset = _getLockPixelOffsets2[1];

        var minOffset = {
          x: this.width / 2 - minLockOffset.x,
          y: this.height / 2 - minLockOffset.y
        };
        var maxOffset = {
          x: this.width / 2 - maxLockOffset.x,
          y: this.height / 2 - maxLockOffset.y
        };

        translate.x = limit(this.minTranslate.x + minOffset.x, this.maxTranslate.x - maxOffset.x, translate.x);
        translate.y = limit(this.minTranslate.y + minOffset.y, this.maxTranslate.y - maxOffset.y, translate.y);
      }

      if (lockAxis === 'x') {
        translate.y = 0;
      } else if (lockAxis === 'y') {
        translate.x = 0;
      }

      this.helper.style[vendorPrefix + 'Transform'] = 'translate3d(' + translate.x + 'px,' + translate.y + 'px, 0)';
    },
    animateNodes: function animateNodes() {
      var _$props5 = this.$props,
          transitionDuration = _$props5.transitionDuration,
          hideSortableGhost = _$props5.hideSortableGhost;

      var nodes = this.manager.getOrderedRefs();
      var deltaScroll = {
        left: this.scrollContainer.scrollLeft - this.initialScroll.left,
        top: this.scrollContainer.scrollTop - this.initialScroll.top
      };
      var sortingOffset = {
        left: this.offsetEdge.left + this.translate.x + deltaScroll.left,
        top: this.offsetEdge.top + this.translate.y + deltaScroll.top
      };
      var scrollDifference = {
        top: window.pageYOffset - this.initialWindowScroll.top,
        left: window.pageXOffset - this.initialWindowScroll.left
      };
      this.newIndex = null;

      for (var i = 0, len = nodes.length; i < len; i++) {
        var node = nodes[i].node;

        var index = node.sortableInfo.index;
        var width = node.offsetWidth;
        var height = node.offsetHeight;
        var offset = {
          width: this.width > width ? width / 2 : this.width / 2,
          height: this.height > height ? height / 2 : this.height / 2
        };

        var translate = {
          x: 0,
          y: 0
        };
        var edgeOffset = nodes[i].edgeOffset;

        // If we haven't cached the node's offsetTop / offsetLeft value

        if (!edgeOffset) {
          nodes[i].edgeOffset = edgeOffset = this.getEdgeOffset(node);
        }

        // Get a reference to the next and previous node
        var nextNode = i < nodes.length - 1 && nodes[i + 1];
        var prevNode = i > 0 && nodes[i - 1];

        // Also cache the next node's edge offset if needed.
        // We need this for calculating the animation in a grid setup
        if (nextNode && !nextNode.edgeOffset) {
          nextNode.edgeOffset = this.getEdgeOffset(nextNode.node);
        }

        // If the node is the one we're currently animating, skip it
        if (index === this.index) {
          if (hideSortableGhost) {
            /*
            * With windowing libraries such as `react-virtualized`, the sortableGhost
            * node may change while scrolling down and then back up (or vice-versa),
            * so we need to update the reference to the new node just to be safe.
            */
            this.sortableGhost = node;
            node.style.visibility = 'hidden';
            node.style.opacity = 0;
          }
          continue;
        }

        if (transitionDuration) {
          node.style[vendorPrefix + 'TransitionDuration'] = transitionDuration + 'ms';
        }

        if (this._axis.x) {
          if (this._axis.y) {
            // Calculations for a grid setup
            if (index < this.index && (sortingOffset.left + scrollDifference.left - offset.width <= edgeOffset.left && sortingOffset.top + scrollDifference.top <= edgeOffset.top + offset.height || sortingOffset.top + scrollDifference.top + offset.height <= edgeOffset.top)) {
              // If the current node is to the left on the same row, or above the node that's being dragged
              // then move it to the right
              translate.x = this.width + this.marginOffset.x;
              if (edgeOffset.left + translate.x > this.containerBoundingRect.width - offset.width) {
                // If it moves passed the right bounds, then animate it to the first position of the next row.
                // We just use the offset of the next node to calculate where to move, because that node's original position
                // is exactly where we want to go
                translate.x = nextNode.edgeOffset.left - edgeOffset.left;
                translate.y = nextNode.edgeOffset.top - edgeOffset.top;
              }
              if (this.newIndex === null) {
                this.newIndex = index;
              }
            } else if (index > this.index && (sortingOffset.left + scrollDifference.left + offset.width >= edgeOffset.left && sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top || sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top + height)) {
              // If the current node is to the right on the same row, or below the node that's being dragged
              // then move it to the left
              translate.x = -(this.width + this.marginOffset.x);
              if (edgeOffset.left + translate.x < this.containerBoundingRect.left + offset.width) {
                // If it moves passed the left bounds, then animate it to the last position of the previous row.
                // We just use the offset of the previous node to calculate where to move, because that node's original position
                // is exactly where we want to go
                translate.x = prevNode.edgeOffset.left - edgeOffset.left;
                translate.y = prevNode.edgeOffset.top - edgeOffset.top;
              }
              this.newIndex = index;
            }
          } else {
            if (index > this.index && sortingOffset.left + scrollDifference.left + offset.width >= edgeOffset.left) {
              translate.x = -(this.width + this.marginOffset.x);
              this.newIndex = index;
            } else if (index < this.index && sortingOffset.left + scrollDifference.left <= edgeOffset.left + offset.width) {
              translate.x = this.width + this.marginOffset.x;
              if (this.newIndex == null) {
                this.newIndex = index;
              }
            }
          }
        } else if (this._axis.y) {
          if (index > this.index && sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top) {
            translate.y = -(this.height + this.marginOffset.y);
            this.newIndex = index;
          } else if (index < this.index && sortingOffset.top + scrollDifference.top <= edgeOffset.top + offset.height) {
            translate.y = this.height + this.marginOffset.y;
            if (this.newIndex == null) {
              this.newIndex = index;
            }
          }
        }
        node.style[vendorPrefix + 'Transform'] = 'translate3d(' + translate.x + 'px,' + translate.y + 'px,0)';
      }

      if (this.newIndex == null) {
        this.newIndex = this.index;
      }
    },
    autoscroll: function autoscroll() {
      var _this7 = this;

      var translate = this.translate;
      var direction = {
        x: 0,
        y: 0
      };
      var speed = {
        x: 1,
        y: 1
      };
      var acceleration = {
        x: 10,
        y: 10
      };

      if (translate.y >= this.maxTranslate.y - this.height / 2) {
        direction.y = 1; // Scroll Down
        speed.y = acceleration.y * Math.abs((this.maxTranslate.y - this.height / 2 - translate.y) / this.height);
      } else if (translate.x >= this.maxTranslate.x - this.width / 2) {
        direction.x = 1; // Scroll Right
        speed.x = acceleration.x * Math.abs((this.maxTranslate.x - this.width / 2 - translate.x) / this.width);
      } else if (translate.y <= this.minTranslate.y + this.height / 2) {
        direction.y = -1; // Scroll Up
        speed.y = acceleration.y * Math.abs((translate.y - this.height / 2 - this.minTranslate.y) / this.height);
      } else if (translate.x <= this.minTranslate.x + this.width / 2) {
        direction.x = -1; // Scroll Left
        speed.x = acceleration.x * Math.abs((translate.x - this.width / 2 - this.minTranslate.x) / this.width);
      }

      if (this.autoscrollInterval) {
        clearInterval(this.autoscrollInterval);
        this.autoscrollInterval = null;
        this.isAutoScrolling = false;
      }

      if (direction.x !== 0 || direction.y !== 0) {
        this.autoscrollInterval = setInterval(function () {
          _this7.isAutoScrolling = true;
          var offset = {
            left: 1 * speed.x * direction.x,
            top: 1 * speed.y * direction.y
          };
          _this7.scrollContainer.scrollTop += offset.top;
          _this7.scrollContainer.scrollLeft += offset.left;
          _this7.translate.x += offset.left;
          _this7.translate.y += offset.top;
          _this7.animateNodes();
        }, 5);
      }
    }
  }
};

// Export Sortable Element Handle Directive
var HandleDirective = {
  bind: function bind(el) {
    el.sortableHandle = true;
  }
};

var SlickList = {
  name: 'slick-list',
  mixins: [ContainerMixin],
  render: function render(h) {
    return h('div', this.$slots.default);
  }
};

var SlickItem = {
  name: 'slick-item',
  mixins: [ElementMixin],
  render: function render(h) {
    return h('div', this.$slots.default);
  }
};

exports.ElementMixin = ElementMixin;
exports.ContainerMixin = ContainerMixin;
exports.HandleDirective = HandleDirective;
exports.SlickList = SlickList;
exports.SlickItem = SlickItem;
exports.arrayMove = arrayMove;

Object.defineProperty(exports, '__esModule', { value: true });

})));


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {/*!
{
  "copywrite": "Copyright (c) 2018-present",
  "date": "2019-03-23T14:29:43.450Z",
  "describe": "",
  "description": "Vue directive to react on clicks outside an element.",
  "file": "v-click-outside-x.min.js",
  "hash": "b169ac562038bd75244d",
  "license": "MIT",
  "version": "4.0.2"
}
*/
!function(e,t){ true?module.exports=t():"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?exports.vClickOutside=t():e.vClickOutside=t()}(function(){"use strict";return"undefined"!=typeof self?self:"undefined"!=typeof window?window:"undefined"!=typeof global?global:Function("return this")()}(),function(){return function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=0)}([function(e,t,n){"use strict";function r(e){return(r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function o(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{},r=Object.keys(n);"function"==typeof Object.getOwnPropertySymbols&&(r=r.concat(Object.getOwnPropertySymbols(n).filter(function(e){return Object.getOwnPropertyDescriptor(n,e).enumerable}))),r.forEach(function(t){u(e,t,n[t])})}return e}function u(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}Object.defineProperty(t,"__esModule",{value:!0}),t.install=function(e){e.directive("click-outside",s)},t.directive=void 0;var i=Object.create(null),c=Object.create(null),f=Object.create(null),l=Object.create(null),a=[i,c],d=function(e,t,n,r){var o=n.target;t[r].forEach(function(t){var r=t.el;if(r!==o&&!r.contains(o)){var u=t.binding;u.modifiers.stop&&n.stopPropagation(),u.modifiers.prevent&&n.preventDefault(),u.value.call(e,n)}})},p=function(e,t){return e?f[t]?f[t]:(f[t]=function(e){d(this,i,e,t)},f[t]):l[t]?l[t]:(l[t]=function(e){d(this,c,e,t)},l[t])},s=Object.defineProperties({},{$_captureInstances:{value:i},$_nonCaptureInstances:{value:c},$_captureEventHandlers:{value:f},$_nonCaptureEventHandlers:{value:l},bind:{value:function(e,t){if("function"!=typeof t.value)throw new TypeError("Binding value must be a function.");var n=t.arg||"click",u=o({},t,{arg:n,modifiers:o({},{capture:!1,prevent:!1,stop:!1},t.modifiers)}),f=u.modifiers.capture,l=f?i:c;Array.isArray(l[n])||(l[n]=[]),1===l[n].push({el:e,binding:u})&&"object"===("undefined"==typeof document?"undefined":r(document))&&document&&document.addEventListener(n,p(f,n),f)}},unbind:{value:function(e){var t=function(t){return t.el!==e};a.forEach(function(e){var n=Object.keys(e);if(n.length){var o=e===i;n.forEach(function(n){var u=e[n].filter(t);u.length?e[n]=u:("object"===("undefined"==typeof document?"undefined":r(document))&&document&&document.removeEventListener(n,p(o,n),o),delete e[n])})}})}},version:{enumerable:!0,value:"4.0.2"}});t.directive=s}])});
//# sourceMappingURL=v-click-outside-x.min.js.map
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(22)))

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_layout_title__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_layout_collapse__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_layout_component_wrapper__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_layout_button__ = __webpack_require__(9);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_layout_repeater__ = __webpack_require__(10);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__components_layout_repeater_item__ = __webpack_require__(11);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__components_layout_popup__ = __webpack_require__(12);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__components_layout_list_table__ = __webpack_require__(13);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__components_layout_list_table_item__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__components_layout_list_table_heading__ = __webpack_require__(15);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__components_layout_tabs__ = __webpack_require__(16);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__components_layout_tabs_panel__ = __webpack_require__(17);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_12__components_form_input__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_13__components_form_textarea__ = __webpack_require__(19);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_14__components_form_switcher__ = __webpack_require__(20);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_15__components_form_iconpicker__ = __webpack_require__(21);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_16__components_form_select__ = __webpack_require__(23);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_17__components_form_f_select__ = __webpack_require__(24);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_18__components_form_checkbox__ = __webpack_require__(25);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_19__components_form_radio__ = __webpack_require__(26);






















Vue.component(__WEBPACK_IMPORTED_MODULE_0__components_layout_title__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_0__components_layout_title__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_1__components_layout_collapse__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_1__components_layout_collapse__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_2__components_layout_component_wrapper__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_2__components_layout_component_wrapper__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_3__components_layout_button__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_3__components_layout_button__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_4__components_layout_repeater__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_4__components_layout_repeater__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_5__components_layout_repeater_item__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_5__components_layout_repeater_item__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_6__components_layout_popup__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_6__components_layout_popup__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_7__components_layout_list_table__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_7__components_layout_list_table__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_8__components_layout_list_table_item__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_8__components_layout_list_table_item__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_9__components_layout_list_table_heading__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_9__components_layout_list_table_heading__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_10__components_layout_tabs__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_10__components_layout_tabs__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_11__components_layout_tabs_panel__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_11__components_layout_tabs_panel__["a" /* default */]);

Vue.component(__WEBPACK_IMPORTED_MODULE_12__components_form_input__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_12__components_form_input__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_13__components_form_textarea__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_13__components_form_textarea__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_14__components_form_switcher__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_14__components_form_switcher__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_15__components_form_iconpicker__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_15__components_form_iconpicker__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_16__components_form_select__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_16__components_form_select__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_17__components_form_f_select__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_17__components_form_f_select__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_18__components_form_checkbox__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_18__components_form_checkbox__["a" /* default */]);
Vue.component(__WEBPACK_IMPORTED_MODULE_19__components_form_radio__["a" /* default */].name, __WEBPACK_IMPORTED_MODULE_19__components_form_radio__["a" /* default */]);

/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
const Title = {
	name: 'cx-vui-title',
	template: '#cx-vui-title',
	props: {}
};

/* harmony default export */ __webpack_exports__["a"] = (Title);

/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__ = __webpack_require__(0);


const Collapse = {
	name: 'cx-vui-collapse',
	template: '#cx-vui-collapse',
	mixins: [__WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		collapsed: {
			type: Boolean,
			default: false
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		}
	},
	data() {
		return {
			state: ''
		};
	},
	mounted() {
		if (this.collapsed) {
			this.state = 'collapsed';
		}
	},
	computed: {
		iconArrow() {
			if ('collapsed' === this.state) {
				return 'dashicons-arrow-right-alt2';
			} else {
				return 'dashicons-arrow-down-alt2';
			}
		}
	},
	methods: {
		switchState() {

			if ('collapsed' === this.state) {
				this.state = '';
			} else {
				this.state = 'collapsed';
			}

			this.$emit('state-switched', this.state);
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Collapse);

/***/ }),
/* 7 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__mixins_wrapper_classes__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const ComponentWrapper = {

	name: 'cx-vui-component-wrapper',
	template: '#cx-vui-component-wrapper',
	mixins: [__WEBPACK_IMPORTED_MODULE_0__mixins_wrapper_classes__["a" /* wrapperClasses */], __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		elementId: {
			type: String
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		preventWrap: {
			type: Boolean,
			default: false
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		}
	},
	computed: {
		wrapperClassesRaw() {

			let classesList = ['cx-vui-component-raw'];

			if (this.wrapperCss) {
				this.wrapperCss.forEach(className => {
					classesList.push(className);
				});
			}

			return classesList;
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (ComponentWrapper);

/***/ }),
/* 8 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
const wrapperClasses = {
	methods: {
		wrapperClasses() {

			var wrapperClassesList = ['cx-vui-component'];

			if (this.wrapperCss.length) {
				this.wrapperCss.forEach(className => {
					wrapperClassesList.push('cx-vui-component--' + className);
				});
			}

			return wrapperClassesList;
		}
	}
};
/* harmony export (immutable) */ __webpack_exports__["a"] = wrapperClasses;


/***/ }),
/* 9 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const Button = {
	name: 'cx-vui-button',
	template: '#cx-vui-button',
	mixins: [__WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		type: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['button', 'submit', 'reset']);
			},
			default: 'button'
		},
		buttonStyle: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['default', 'accent', 'link-accent', 'link-error']);
			},
			default: 'default'
		},
		size: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['default', 'mini', 'link']);
			},
			default: 'default'
		},
		disabled: {
			type: Boolean,
			default: false
		},
		loading: {
			type: Boolean,
			default: false
		},
		customCss: {
			type: String
		},
		url: {
			type: String
		},
		target: {
			type: String
		},
		tagName: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['a', 'button']);
			},
			default: 'button'
		},
		elementId: {
			type: String
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		}
	},
	data() {
		return {
			baseClass: 'cx-vui-button'
		};
	},
	computed: {
		classesList() {

			let classesList = [this.baseClass, this.baseClass + '--style-' + this.buttonStyle, this.baseClass + '--size-' + this.size];

			if (this.loading) {
				classesList.push(this.baseClass + '--loading');
			}

			if (this.disabled) {
				classesList.push(this.baseClass + '--disabled');
			}

			if (this.customCss) {
				classesList.push(this.customCss);
			}

			return classesList;
		},
		tagAtts() {

			let atts = {};

			if ('a' === this.tagName) {

				if (this.url) {
					atts.href = this.url;
				}

				if (this.target) {
					atts.target = this.target;
				}
			} else {
				atts.type = this.type;
			}

			return atts;
		}
	},
	methods: {
		handleClick() {

			if (this.loading || this.disabled) {
				return;
			}

			this.$emit('click', event);
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Button);

/***/ }),
/* 10 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue_slicksort__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue_slicksort___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue_slicksort__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const Repeater = {
	name: 'cx-vui-repeater',
	template: '#cx-vui-repeater',
	mixins: [__WEBPACK_IMPORTED_MODULE_0_vue_slicksort__["ContainerMixin"], __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		buttonLabel: {
			type: String
		},
		buttonStyle: {
			type: String,
			default: 'accent'
		},
		buttonSize: {
			type: String,
			default: 'default'
		},
		value: {
			type: Array,
			default() {
				return [];
			}
		},
		distance: {
			type: Number,
			default: 20
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		}
	},
	data() {
		return {
			inFocus: false
		};
	},
	methods: {
		handleClick(event) {
			this.$emit('add-new-item', event);
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Repeater);

/***/ }),
/* 11 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue_slicksort__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue_slicksort___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue_slicksort__);


const RepeaterItem = {
	name: 'cx-vui-repeater-item',
	template: '#cx-vui-repeater-item',
	mixins: [__WEBPACK_IMPORTED_MODULE_0_vue_slicksort__["ElementMixin"]],
	props: {
		title: {
			type: String
		},
		subtitle: {
			type: String
		},
		collapsed: {
			type: Boolean,
			default: true
		},
		index: {
			type: Number
		}
	},
	data() {
		return {
			fieldData: this.field,
			isCollapsed: this.collapsed,
			showConfirmTip: false
		};
	},
	methods: {
		handleCopy() {
			this.$emit('clone-item', this.index);
		},
		handleDelete() {
			this.showConfirmTip = true;
		},
		confrimDeletion() {
			this.showConfirmTip = false;
			this.$emit('delete-item', this.index);
		},
		cancelDeletion() {
			this.showConfirmTip = false;
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (RepeaterItem);

/***/ }),
/* 12 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
const Popup = {
	name: 'cx-vui-popup',
	template: '#cx-vui-popup',
	props: {
		value: {
			type: Boolean,
			default: false
		},
		overlay: {
			type: Boolean,
			default: true
		},
		close: {
			type: Boolean,
			default: true
		},
		header: {
			type: Boolean,
			default: true
		},
		footer: {
			type: Boolean,
			default: true
		},
		okLabel: {
			type: String,
			default: 'OK'
		},
		cancelLabel: {
			type: String,
			default: 'Cancel'
		},
		bodyWidth: {
			type: String,
			default: 'auto'
		}
	},
	data() {
		return {
			currentValue: this.value
		};
	},
	watch: {
		value(val) {
			this.setCurrentValue(val);
		}
	},
	methods: {
		handleCancel() {
			this.setCurrentValue(false);
			this.$emit('input', false);
			this.$emit('on-cancel');
		},
		handleOk() {
			this.setCurrentValue(false);
			this.$emit('input', false);
			this.$emit('on-ok');
		},
		setCurrentValue(value) {

			if (this.currentValue === value) {
				return;
			}

			this.currentValue = value;
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Popup);

/***/ }),
/* 13 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__ = __webpack_require__(0);


const ListTable = {
	name: 'cx-vui-list-table',
	template: '#cx-vui-list-table',
	mixins: [__WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		conditions: {
			type: Array,
			default() {
				return [];
			}
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (ListTable);

/***/ }),
/* 14 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__ = __webpack_require__(0);


const ListTableItem = {
	name: 'cx-vui-list-table-item',
	template: '#cx-vui-list-table-item',
	mixins: [__WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		slots: {
			type: Array,
			default() {
				return [];
			}
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (ListTableItem);

/***/ }),
/* 15 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
const ListTableHeading = {
	name: 'cx-vui-list-table-heading',
	template: '#cx-vui-list-table-heading',
	props: {
		slots: {
			type: Array,
			default() {
				return [];
			}
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (ListTableHeading);

/***/ }),
/* 16 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__utils_assist__ = __webpack_require__(1);



const Tabs = {
	name: 'cx-vui-tabs',
	template: '#cx-vui-tabs',
	mixins: [__WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		value: {
			type: [String, Number],
			default: ''
		},
		name: {
			type: String,
			default: ''
		},
		invert: {
			type: Boolean,
			default: false
		},
		inPanel: {
			type: Boolean,
			default: false
		},
		layout: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_1__utils_assist__["b" /* oneOf */])(value, ['horizontal', 'vertical']);
			},
			default: 'horizontal'
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		}
	},
	data() {
		return {
			navList: [],
			activeTab: this.value
		};
	},
	mounted() {

		const tabs = this.getTabs();

		this.navList = tabs;

		if (!this.activeTab) {
			this.activeTab = tabs[0].name;
		}

		this.updateState();
	},
	methods: {
		isActive(name) {
			return name === this.activeTab;
		},
		onTabClick(tab) {
			this.activeTab = tab;
			this.$emit('input', this.activeTab);
			this.updateState();
		},
		updateState() {
			const tabs = this.getTabs();
			tabs.forEach(tab => {
				tab.show = this.activeTab === tab.name;
			});
		},
		getTabs() {

			const allTabs = this.$children.filter(item => {
				return 'cx-vui-tabs-panel' === item.$options.name;
			});
			const tabs = [];

			allTabs.forEach(item => {
				if (item.tab && this.name) {
					if (item.tab === this.name) {
						tabs.push(item);
					}
				} else {
					tabs.push(item);
				}
			});

			return tabs;
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Tabs);

/***/ }),
/* 17 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
const TabsPanel = {
	name: 'cx-vui-tabs-panel',
	template: '#cx-vui-tabs-panel',
	props: {
		tab: {
			type: String,
			default: ''
		},
		name: {
			type: String,
			default: ''
		},
		label: {
			type: String,
			default: ''
		}
	},
	data() {
		return {
			show: false
		};
	},
	methods: {}
};

/* harmony default export */ __webpack_exports__["a"] = (TabsPanel);

/***/ }),
/* 18 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const Input = {

	name: 'cx-vui-input',
	template: '#cx-vui-input',
	mixins: [__WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		type: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['text', 'textarea', 'password', 'url', 'email', 'date', 'number', 'tel']);
			},
			default: 'text'
		},
		value: {
			type: [String, Number],
			default: ''
		},
		size: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['small', 'large', 'default', 'fullwidth']);
			},
			default: 'default'
		},
		placeholder: {
			type: String,
			default: ''
		},
		maxlength: {
			type: Number
		},
		disabled: {
			type: Boolean,
			default: false
		},
		error: {
			type: Boolean,
			default: false
		},
		readonly: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		autofocus: {
			type: Boolean,
			default: false
		},
		autocomplete: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['on', 'off']);
			},
			default: 'off'
		},
		elementId: {
			type: String
		},
		conditions: {
			type: Array,
			default: function () {
				return [];
			}
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			currentValue: this.value,
			currentId: this.elementId
		};
	},
	watch: {
		value(val) {
			this.setCurrentValue(val);
		}
	},
	mounted() {
		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}
	},
	computed: {
		controlClasses() {

			var classesList = ['cx-vui-input'];

			classesList.push('size-' + this.size);

			if (this.error) {
				classesList.push('has-error');
			}

			return classesList;
		}
	},
	methods: {
		handleEnter(event) {
			this.$emit('on-enter', event);
		},
		handleKeydown(event) {
			this.$emit('on-keydown', event);
		},
		handleKeypress(event) {
			this.$emit('on-keypress', event);
		},
		handleKeyup(event) {
			this.$emit('on-keyup', event);
		},
		handleFocus(event) {
			this.$emit('on-focus', event);
		},
		handleBlur(event) {
			this.$emit('on-blur', event);
		},
		handleInput(event) {
			let value = event.target.value;
			this.$emit('input', value);
			this.setCurrentValue(value);
			this.$emit('on-change', event);
		},
		handleChange(event) {
			this.$emit('on-input-change', event);
		},
		setCurrentValue(value) {

			if (value === this.currentValue) {
				return;
			}

			this.currentValue = value;
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Input);

/***/ }),
/* 19 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const Input = {

	name: 'cx-vui-textarea',
	template: '#cx-vui-textarea',
	mixins: [__WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		value: {
			type: [String, Number],
			default: ''
		},
		size: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['small', 'large', 'default', 'fullwidth']);
			},
			default: 'default'
		},
		placeholder: {
			type: String,
			default: ''
		},
		rows: {
			type: Number
		},
		disabled: {
			type: Boolean,
			default: false
		},
		error: {
			type: Boolean,
			default: false
		},
		readonly: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		elementId: {
			type: String
		},
		conditions: {
			type: Array,
			default: function () {
				return [];
			}
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			currentValue: this.value,
			currentId: this.elementId
		};
	},
	watch: {
		value(val) {
			this.setCurrentValue(val);
		}
	},
	mounted() {
		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}
	},
	computed: {
		controlClasses() {

			var classesList = ['cx-vui-textarea'];

			classesList.push('size-' + this.size);

			if (this.error) {
				classesList.push('has-error');
			}

			return classesList;
		}
	},
	methods: {
		handleEnter(event) {
			this.$emit('on-enter', event);
		},
		handleKeydown(event) {
			this.$emit('on-keydown', event);
		},
		handleKeypress(event) {
			this.$emit('on-keypress', event);
		},
		handleKeyup(event) {
			this.$emit('on-keyup', event);
		},
		handleFocus(event) {
			this.$emit('on-focus', event);
		},
		handleBlur(event) {
			this.$emit('on-blur', event);
		},
		handleInput(event) {
			let value = event.target.value;
			this.$emit('input', value);
			this.setCurrentValue(value);
			this.$emit('on-change', event);
		},
		handleChange(event) {
			this.$emit('on-input-change', event);
		},
		setCurrentValue(value) {

			if (value === this.currentValue) {
				return;
			}

			this.currentValue = value;
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Input);

/***/ }),
/* 20 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__ = __webpack_require__(0);


const Switcher = {

	name: 'cx-vui-switcher',
	template: '#cx-vui-switcher',
	mixins: [__WEBPACK_IMPORTED_MODULE_0__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		value: {
			type: [String, Number, Boolean],
			default: ''
		},
		disabled: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		elementId: {
			type: String
		},
		conditions: {
			type: Array,
			default: function () {
				return [];
			}
		},
		returnTrue: {
			type: [String, Number, Boolean],
			default: true
		},
		returnFalse: {
			type: [String, Number, Boolean],
			default: ''
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			currentValue: this.value,
			currentId: this.elementId,
			isOn: false,
			inFocus: false
		};
	},
	watch: {
		value(val) {

			this.setCurrentValue(val);

			if (val === this.returnTrue) {
				this.isOn = true;
			} else {
				this.isOn = false;
			}
		}
	},
	mounted() {

		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}

		if (this.value === this.returnTrue) {
			this.isOn = true;
		}
	},
	methods: {
		handleEnter(event) {
			this.$emit('on-enter', event);
			this.switchState();
			this.inFocus = true;
		},
		handleFocus(event) {
			this.inFocus = true;
			this.$emit('on-focus', event);
		},
		handleBlur(event) {
			this.inFocus = false;
			this.$emit('on-blur', event);
		},
		switchState() {

			let value;

			this.isOn = !this.isOn;

			if (this.isOn) {
				value = this.returnTrue;
			} else {
				value = this.returnFalse;
			}

			this.$emit('input', value);
			this.setCurrentValue(value);
			this.$emit('on-change', event);

			this.inFocus = false;
		},
		handleChange(event) {
			this.$emit('on-input-change', event);
		},
		setCurrentValue(value) {

			if (value === this.currentValue) {
				return;
			}

			this.currentValue = value;
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Switcher);

/***/ }),
/* 21 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_v_click_outside_x__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_v_click_outside_x___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_v_click_outside_x__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__mixins_check_conditions__ = __webpack_require__(0);




const Iconpicker = {

	name: 'cx-vui-iconpicker',
	template: '#cx-vui-iconpicker',
	mixins: [__WEBPACK_IMPORTED_MODULE_2__mixins_check_conditions__["a" /* checkConditions */]],
	directives: { clickOutside: __WEBPACK_IMPORTED_MODULE_0_v_click_outside_x__["directive"] },
	props: {
		value: {
			type: [String],
			default: ''
		},
		size: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_1__utils_assist__["b" /* oneOf */])(value, ['small', 'large', 'default', 'fullwidth']);
			},
			default: 'default'
		},
		placeholder: {
			type: String,
			default: ''
		},
		disabled: {
			type: Boolean,
			default: false
		},
		readonly: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		autofocus: {
			type: Boolean,
			default: false
		},
		elementId: {
			type: String
		},
		autocomplete: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_1__utils_assist__["b" /* oneOf */])(value, ['on', 'off']);
			},
			default: 'off'
		},
		conditions: {
			type: Array,
			default: function () {
				return [];
			}
		},
		iconBase: {
			type: String,
			default: ''
		},
		iconPrefix: {
			type: String,
			default: ''
		},
		icons: {
			type: Array,
			default: function () {
				return [];
			}
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			currentValue: this.value,
			currentId: this.elementId,
			filterQuery: '',
			panelActive: false,
			prefixedIcons: []
		};
	},
	watch: {
		value(val) {
			this.setCurrentValue(val);
		}
	},
	mounted() {

		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}

		this.icons.forEach(icon => {
			this.prefixedIcons.push(this.iconPrefix + icon);
		});
	},
	computed: {
		filteredIcons() {
			if (!this.filterQuery) {
				return this.prefixedIcons;
			} else {
				return this.prefixedIcons.filter(icon => {
					return icon.includes(this.filterQuery);
				});
			}
		}
	},
	methods: {
		handleEnter(event) {
			this.$emit('on-enter', event);
		},
		handleKeydown(event) {
			this.$emit('on-keydown', event);
		},
		handleKeypress(event) {
			this.$emit('on-keypress', event);
		},
		handleKeyup(event) {
			this.$emit('on-keyup', event);
		},
		handleFocus(event) {
			this.panelActive = true;
			this.$emit('on-focus', event);
		},
		handleBlur(event) {
			this.$emit('on-blur', event);
		},
		seclectIcon(icon) {

			this.$emit('input', icon);
			this.setCurrentValue(icon);
			this.$emit('on-change', icon);

			this.closePanel();
		},
		handleInput(event) {

			let value = event.target.value;

			this.filterQuery = value;
			this.$emit('input', value);
			this.setCurrentValue(value);
			this.$emit('on-change', event);
		},
		handleChange(event) {
			this.$emit('on-input-change', event);
		},
		setCurrentValue(value) {

			if (value === this.currentValue) {
				return;
			}

			this.currentValue = value;
		},
		onClickOutside(event) {
			this.closePanel();
		},
		closePanel() {

			if (this.panelActive) {

				this.panelActive = false;
				this.filterQuery = '';

				this.$emit('on-panel-closed');
			}
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Iconpicker);

/***/ }),
/* 22 */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),
/* 23 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const SelectPlain = {

	name: 'cx-vui-select',
	template: '#cx-vui-select',
	mixins: [__WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		value: {
			type: [String, Number, Array],
			default: ''
		},
		size: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['small', 'large', 'default', 'fullwidth']);
			},
			default: 'default'
		},
		placeholder: {
			type: String,
			default: ''
		},
		optionsList: {
			type: Array,
			default: function () {
				return [];
			}
		},
		disabled: {
			type: Boolean,
			default: false
		},
		readonly: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		multiple: {
			type: Boolean,
			default: false
		},
		elementId: {
			type: String
		},
		conditions: {
			type: Array,
			default: function () {
				return [];
			}
		},
		remote: {
			type: Boolean,
			default: false
		},
		remoteCallback: {
			type: Function
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			options: this.optionsList,
			currentValue: this.value,
			currentId: this.elementId
		};
	},
	watch: {
		value(val) {
			this.storeCurrentValue(val);
		},
		optionsList(options) {
			this.setOptions(options);
		}
	},
	created() {

		if (this.multiple) {

			if (this.currentValue && 'object' !== typeof this.currentValue) {
				this.currentValue = [this.currentValue];
			}
		} else {

			if (this.currentValue && 'object' === typeof this.currentValue) {
				this.currentValue = this.currentValue[0];
			}
		}
	},
	mounted() {

		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}

		if (this.remote && this.remoteCallback) {

			const promise = this.remoteCallback();

			if (promise && promise.then) {
				promise.then(options => {
					if (options) {
						this.options = options;
					}
				});
			}
		}
	},
	methods: {
		controlClasses() {
			var classesList = ['cx-vui-select'];
			classesList.push('size-' + this.size);
			return classesList;
		},
		handleFocus(event) {
			this.$emit('on-focus', event);
		},
		handleBlur(event) {
			this.$emit('on-blur', event);
		},
		handleInput() {
			this.$emit('input', this.currentValue);
			this.$emit('on-change', event);
		},
		storeCurrentValue(value) {

			if (this.multiple) {

				if (Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, this.currentValue)) {
					return;
				}

				if ('object' === typeof value) {
					if ('[object Array]' === Object.prototype.toString.call(value)) {
						this.currentValues.concat(value);
					} else {
						this.currentValues.push(value);
					}
				} else {
					this.currentValue.push(value);
				}
			} else {

				if (value === this.currentValue) {
					return;
				}

				this.currentValue = value;
			}
		},
		setOptions(options) {
			this.options = options;
		},
		isOptionSelected(option) {

			if (!this.currentValue) {
				return false;
			}

			if (this.multiple) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(option.value, this.currentValue);
			} else {
				return option.value === this.currentValue;
			}
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (SelectPlain);

/***/ }),
/* 24 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_v_click_outside_x__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_v_click_outside_x___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_v_click_outside_x__);




const FilterableSelect = {

	name: 'cx-vui-f-select',
	template: '#cx-vui-f-select',
	mixins: [__WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	directives: { clickOutside: __WEBPACK_IMPORTED_MODULE_2_v_click_outside_x__["directive"] },
	props: {
		value: {
			type: [String, Number, Array],
			default: ''
		},
		placeholder: {
			type: String,
			default: ''
		},
		optionsList: {
			type: Array,
			default: function () {
				return [];
			}
		},
		disabled: {
			type: Boolean,
			default: false
		},
		readonly: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		multiple: {
			type: Boolean,
			default: false
		},
		elementId: {
			type: String
		},
		autocomplete: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['on', 'off']);
			},
			default: 'off'
		},
		conditions: {
			type: Array,
			default: function () {
				return [];
			}
		},
		remote: {
			type: Boolean,
			default: false
		},
		remoteCallback: {
			type: Function
		},
		remoteTrigger: {
			type: Number,
			default: 3
		},
		remoteTriggerMessage: {
			type: String,
			default: 'Please enter %d char(s) to start search'
		},
		notFoundMeassge: {
			type: String,
			default: 'There is no items find matching this query'
		},
		loadingMessage: {
			type: String,
			default: 'Loading...'
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			options: this.optionsList,
			currentValues: this.value,
			currentId: this.elementId,
			selectedOptions: [],
			query: '',
			inFocus: false,
			optionInFocus: false,
			loading: false,
			loaded: false
		};
	},
	watch: {
		value(newValue, oldValue) {

			if (this.multiple) {

				if (Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["a" /* arraysEqual */])(newValue, oldValue)) {
					return;
				}
			} else {

				if (newValue === oldValue) {
					return;
				}
			}

			this.storeValues(newValue);
		},
		optionsList(options) {
			this.setOptions(options);
		}
	},
	created() {

		if (!this.currentValues) {
			this.currentValues = [];
		} else if ('object' !== typeof this.currentValues) {
			if ('[object Array]' === Object.prototype.toString.call(this.currentValues)) {} else {
				this.currentValues = [this.currentValues];
			}
		}
	},
	mounted() {

		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}

		if (this.remote && this.remoteCallback && this.currentValues.length) {
			this.remoteUpdateSelected();
		} else if (this.currentValues.length) {
			this.options.forEach(option => {
				if (Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(option.value, this.currentValues)) {
					this.selectedOptions.push(option);
				}
			});
		}
	},
	computed: {
		filteredOptions() {
			if (!this.query) {
				return this.options;
			} else {
				return this.options.filter(option => {
					if (this.remote) {
						return true;
					} else {
						return option.label.includes(this.query) || option.value.includes(this.query);
					}
				});
			}
		},
		parsedRemoteTriggerMessage() {
			return this.remoteTriggerMessage.replace(/\%d/g, this.charsDiff);
		},
		charsDiff() {

			let queryLength = 0;

			if (this.query) {
				queryLength = this.query.length;
			}

			return this.remoteTrigger - queryLength;
		}
	},
	methods: {
		remoteUpdateSelected() {

			this.loading = true;

			const promise = this.remoteCallback(this.query, this.currentValues);

			if (promise && promise.then) {
				promise.then(options => {
					if (options) {
						this.selectedOptions = options;
						this.loaded = true;
						this.loading = false;
					}
				});
			}
		},
		handleFocus(event) {
			this.inFocus = true;
			this.$emit('on-focus', event);
		},
		handleOptionsNav(event) {

			// next
			if ('ArrowUp' === event.key || 'Tab' === event.key) {
				this.navigateOptions(-1);
			}
			// prev
			if ('ArrowDown' === event.key) {
				this.navigateOptions(1);
			}
		},
		navigateOptions(direction) {

			if (false === this.optionInFocus) {
				this.optionInFocus = -1;
			}

			let index = this.optionInFocus + direction;
			let maxLength = this.options.length - 1;

			if (maxLength < 0) {
				maxLength = 0;
			}

			if (index < 0) {
				index = 0;
			} else if (index > maxLength) {
				index = maxLength;
			}

			this.optionInFocus = index;
		},
		onClickOutside(event) {

			if (this.inFocus) {
				this.inFocus = false;
				this.$emit('on-blur', event);
			}
		},
		handleInput(event) {

			let value = event.target.value;

			this.query = value;

			this.$emit('input', this.currentValues);
			this.$emit('on-change', event);

			if (!this.inFocus) {
				this.inFocus = true;
			}

			if (this.remote && this.remoteCallback && this.charsDiff <= 0 && !this.loading && !this.loaded) {

				this.loading = true;

				const promise = this.remoteCallback(this.query, []);

				if (promise && promise.then) {
					promise.then(options => {
						if (options) {
							this.options = options;
							this.loaded = true;
							this.loading = false;
						}
					});
				}
			} else if (this.remote && this.remoteCallback && this.loaded && this.charsDiff > 0) {
				this.resetRemoteOptions();
			}
		},
		handleEnter() {

			if (false === this.optionInFocus || !this.options[this.optionInFocus]) {
				return;
			}

			let value = this.options[this.optionInFocus].value;

			this.handleResultClick(value);
		},
		handleResultClick(value) {

			if (Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, this.currentValues)) {
				this.removeValue(value);
			} else {
				this.storeValues(value);
			}

			this.$emit('input', this.currentValues);
			this.$emit('on-change', this.currentValues);

			this.inFocus = false;
			this.optionInFocus = false;
			this.query = '';

			if (this.remote && this.remoteCallback && this.loaded) {
				this.resetRemoteOptions();
			}
		},
		resetRemoteOptions() {
			this.options = [];
			this.loaded = false;
		},
		removeValue(value) {
			this.currentValues.splice(this.currentValues.indexOf(value), 1);
			this.removeFromSelected(value);
		},
		removeFromSelected(value) {
			this.selectedOptions.forEach((option, index) => {
				if (option.value === value) {
					this.selectedOptions.splice(index, 1);
				}
			});
		},
		pushToSelected(value, single) {
			this.options.forEach(option => {
				if (option.value === value) {
					if (!single) {
						this.selectedOptions.push(option);
					} else {
						this.selectedOptions = [option];
					}
				}
			});
		},
		storeValues(value) {

			if (Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, this.currentValues)) {
				return;
			}

			if (this.multiple) {

				if ('object' === typeof value) {

					if ('[object Array]' === Object.prototype.toString.call(value)) {

						value.forEach(singleVal => {
							if (!Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(singleVal, this.currentValues)) {
								this.currentValues.push(value);
								this.pushToSelected(singleVal);
							}
						});
					} else {
						this.currentValues.push(value);
						this.pushToSelected(value);
					}
				} else {
					this.currentValues.push(value);
					this.pushToSelected(value);
				}
			} else {

				if ('object' === typeof value) {

					if ('[object Array]' === Object.prototype.toString.call(value)) {

						this.currentValues = value;

						value.forEach(singleVal => {
							this.pushToSelected(singleVal, true);
						});
					} else {
						this.currentValues = [value];
						this.pushToSelected(value, true);
					}
				} else {
					this.currentValues = [value];
					this.pushToSelected(value, true);
				}
			}
		},
		setOptions(options) {
			this.options = options;
		},
		isOptionSelected(option) {

			if (!this.currentValues) {
				return false;
			}

			return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(option.value, this.currentValues);
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (FilterableSelect);

/***/ }),
/* 25 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const Checkbox = {

	name: 'cx-vui-checkbox',
	template: '#cx-vui-checkbox',
	mixins: [__WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		returnType: {
			validator(value) {
				return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, ['array', 'object', 'single']);
			},
			default: 'object'
		},
		value: {
			default: ''
		},
		disabled: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		optionsList: {
			type: Array,
			default() {
				return [];
			}
		},
		returnTrue: {
			type: [Boolean, String, Number],
			default: true
		},
		returnFalse: {
			type: [Boolean, String, Number],
			default: false
		},
		elementId: {
			type: String
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			currentValues: this.value,
			currentId: this.elementId,
			optionInFocus: null
		};
	},
	watch: {
		value(val) {
			this.setCurrentValues(val);
		}
	},
	mounted() {
		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}
	},
	computed: {
		inputType() {
			if ('array' === this.returnType) {
				return 'checkbox';
			} else {
				return 'hidden';
			}
		}
	},
	methods: {
		inputValue(optionValue) {

			if ('checkbox' === this.inputType) {
				return this.returnTrue;
			} else {
				if (this.isChecked(optionValue)) {
					return this.returnTrue;
				} else {
					return this.returnFalse;
				}
			}
		},
		isChecked(optionValue) {

			if (!this.currentValues) {
				return false;
			}

			switch (this.returnType) {

				case 'single':

					return optionValue === this.currentValues;

				case 'array':

					return Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(optionValue, this.currentValues);

				case 'object':

					if (!this.currentValues[optionValue]) {
						return false;
					} else {
						if (this.currentValues[optionValue] === this.returnTrue) {
							return true;
						} else {
							return false;
						}
					}

					break;

			};
		},
		handleEnter(event) {
			this.$emit('on-enter', event);
		},
		handleClick(event) {
			this.$emit('on-click', event);
		},
		handleFocus(event, value) {

			if (this.disabled) {
				return;
			}

			this.optionInFocus = value;
			this.$emit('on-focus', event, value);
		},
		handleBlur(event, value) {

			if (this.disabled) {
				return;
			}

			if (value === this.optionInFocus) {
				this.optionInFocus = null;
			}

			this.$emit('on-blur', event, value);
		},
		handleParentFocus() {

			if (this.disabled) {
				return;
			}

			if (null === this.optionInFocus && this.optionsList.length) {
				this.optionInFocus = this.optionsList[0].value;
			}
		},
		handleInput(event, value) {

			if (this.disabled) {
				return;
			}

			this.updateValueState(value);

			this.$emit('input', this.currentValues);
			this.$emit('on-change', event);
		},
		isOptionInFocus(value) {
			return value === this.optionInFocus;
		},
		updateValueState(value) {

			switch (this.returnType) {

				case 'single':

					if (value !== this.currentValues) {
						this.currentValues = value;
					}

					break;

				case 'array':

					if (!Object(__WEBPACK_IMPORTED_MODULE_0__utils_assist__["b" /* oneOf */])(value, this.currentValues)) {
						this.currentValues.push(value);
					} else {
						this.currentValues.splice(this.currentValues.indexOf(value), 1);
					}

					break;

				case 'object':

					if (!this.currentValues[value]) {
						this.$set(this.currentValues, value, this.returnTrue);
					} else {
						if (this.currentValues[value] === this.returnTrue) {
							this.$set(this.currentValues, value, this.returnFalse);
						} else {
							this.$set(this.currentValues, value, this.returnTrue);
						}
					}

					break;
			}
		},
		setCurrentValues(value) {

			switch (this.returnType) {

				case 'single':

					if (value !== this.currentValues) {
						this.currentValues = value;
					}

					break;

				case 'array':
				case 'object':

					this.currentValues = value;
					break;

			};
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Checkbox);

/***/ }),
/* 26 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_assist__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__ = __webpack_require__(0);



const Radio = {

	name: 'cx-vui-radio',
	template: '#cx-vui-radio',
	mixins: [__WEBPACK_IMPORTED_MODULE_1__mixins_check_conditions__["a" /* checkConditions */]],
	props: {
		value: {
			default: ''
		},
		disabled: {
			type: Boolean,
			default: false
		},
		name: {
			type: String
		},
		optionsList: {
			type: Array,
			default() {
				return [];
			}
		},
		elementId: {
			type: String
		},
		conditions: {
			type: Array,
			default() {
				return [];
			}
		},
		// Wrapper related props (should be passed into wrapper component)
		preventWrap: {
			type: Boolean,
			default: false
		},
		label: {
			type: String
		},
		description: {
			type: String
		},
		wrapperCss: {
			type: Array,
			default: function () {
				return [];
			}
		}
	},
	data() {
		return {
			currentValue: this.value,
			currentId: this.elementId,
			optionInFocus: null
		};
	},
	watch: {
		value(val) {
			this.setCurrentValue(val);
		}
	},
	mounted() {
		if (!this.currentId && this.name) {
			this.currentId = 'cx_' + this.name;
		}
	},
	methods: {
		handleEnter(event) {
			this.$emit('on-enter', event);
		},
		handleClick(event) {
			this.$emit('on-click', event);
		},
		handleFocus(event, value) {

			if (this.disabled) {
				return;
			}

			this.optionInFocus = value;
			this.$emit('on-focus', event, value);
		},
		handleBlur(event, value) {

			if (this.disabled) {
				return;
			}

			if (value === this.optionInFocus) {
				this.optionInFocus = null;
			}

			this.$emit('on-blur', event, value);
		},
		handleInput(event, value) {

			if (this.disabled) {
				return;
			}

			this.setCurrentValue(value);

			this.$emit('input', this.currentValue);
			this.$emit('on-change', event);
		},
		isOptionInFocus(value) {
			return value === this.optionInFocus;
		},
		setCurrentValue(value) {

			if (value !== this.currentValue) {
				this.currentValue = value;
			}
		}
	}
};

/* harmony default export */ __webpack_exports__["a"] = (Radio);

/***/ })
/******/ ]);