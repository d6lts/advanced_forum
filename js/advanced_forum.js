//$Id$
(function ($) {
  if (!Drupal.advanced_forum) {
    Drupal.advanced_forum = {};
  }

  /**
   * Object to store state.
   *
   * This object will remember the state of collapsible containers. The first
   * time a state is requested, it will check the cookie and set up the variable.
   * If a state has been changed, when the window is unloaded the state will be
   * saved.
   */
  Drupal.advanced_forum.Collapsible = {
    state: {},
    stateLoaded: false,
    stateChanged: false,
    cookieString: 'advanced_forum-collapsible-state=',

    /**
     * Get the current collapsed state of a container.
     *
     * If set to 1, the container is open. If set to -1, the container is
     * collapsed. If unset the state is unknown, and the default state should
     * be used.
     */
    getState: function (id) {
      if (!this.stateLoaded) {
        this.loadCookie();
      }

      return this.state[id];
    },

    /**
     * Set the collapsed state of a container for subsequent page loads.
     *
     * Set the state to 1 for open, -1 for collapsed.
     */
    setState: function (id, state) {
      if (!this.stateLoaded) {
        this.loadCookie();
      }

      this.state[id] = state;

      if (!this.stateChanged) {
        this.stateChanged = true;
        $(window).unload(this.unload);
      }
    },

    /**
     * Check the cookie and load the state variable.
     */
    loadCookie: function () {
      // If there is a previous instance of this cookie
      if (document.cookie.length > 0) {
        // Get the number of characters that have the list of values
        // from our string index.
        offset = document.cookie.indexOf(this.cookieString);

        // If its positive, there is a list!
        if (offset != -1) {
          offset += this.cookieString.length;
          var end = document.cookie.indexOf(';', offset);
          if (end == -1) {
            end = document.cookie.length;
          }

          // Get a list of all values that are saved on our string
          var cookie = unescape(document.cookie.substring(offset, end));

          if (cookie != '') {
            var cookieList = cookie.split(',');
            for (var i = 0; i < cookieList.length; i++) {
              var info = cookieList[i].split(':');
              this.state[info[0]] = info[1];
            }
          }
        }
      }

      this.stateLoaded = true;
    },

    /**
     * Turn the state variable into a string and store it in the cookie.
     */
    storeCookie: function () {
      var cookie = '';

      // Get a list of IDs, saparated by comma
      for (i in this.state) {
        if (cookie != '') {
          cookie += ',';
        }
        cookie += i + ':' + this.state[i];
      }

      // Save this values on the cookie
      document.cookie = this.cookieString + escape(cookie) + ';path=/';
    },

    /**
     * Respond to the unload event by storing the current state.
     */
    unload: function() {
      Drupal.advanced_forum.Collapsible.storeCookie();
    }
  };

  // Set up an array for callbacks.
  Drupal.advanced_forum.CollapsibleCallbacks = [];
  Drupal.advanced_forum.CollapsibleCallbacksAfterToggle = [];

  /**
   * Bind collapsible behavior to a given container.
   */
  Drupal.advanced_forum.bindCollapsible = function () {
    var $container = $(this);
    var handle = $container.children('.forum-table thead');
    var content = $container.children('.forum-table tbody');

    if (content.length) {
      var toggle = $('<span class="advanced-forum-toggle">&nbsp;</span>');
      handle.find('th:last-child').append(toggle);
      var state = Drupal.advanced_forum.Collapsible.getState($container.attr('id'));
      if (state == 1) {
        $container.removeClass('advanced-forum-collapsed');
      }
      else if (state == -1) {
        $container.addClass('advanced-forum-collapsed');
      }

      // If we should start collapsed, do so:
      if ($container.hasClass('advanced-forum-collapsed')) {
        toggle.toggleClass('advanced-forum-toggle-collapsed');
        content.hide();
      }

      var afterToggle = function () {
        if (Drupal.advanced_forum.CollapsibleCallbacksAfterToggle) {
          for (i in Drupal.advanced_forum.CollapsibleCallbacksAfterToggle) {
            Drupal.advanced_forum.CollapsibleCallbacksAfterToggle[i]($container, handle, content, toggle);
          }
        }
      }

      var clickMe = function () {
        if (Drupal.advanced_forum.CollapsibleCallbacks) {
          for (i in Drupal.advanced_forum.CollapsibleCallbacks) {
            Drupal.advanced_forum.CollapsibleCallbacks[i]($container, handle, content, toggle);
          }
        }

        if (Drupal.settings.advanced_forum.effect == 'toggle') {
          content.toggle(0, afterToggle);
        } else {
          //content.fadeToggle(0, afterToggle); // jquery 1.4.4 and up only
          content.animate({opacity: 'toggle'}, 150, afterToggle);
        }

        toggle.toggleClass('advanced-forum-toggle-collapsed');

        var state = toggle.hasClass('advanced-forum-toggle-collapsed') ? -1 : 1;
        Drupal.advanced_forum.Collapsible.setState($container.attr('id'), state);
      }

      // Make toggle clickable.
      toggle.click(clickMe);
    }
  };

  /**
   * Support Drupal's 'behaviors' system for binding.
   */
  Drupal.behaviors.advanced_forumCollapsible = function(context) {
    $('.forum-table:not(.advanced-forum-collapsible-processed)', context)
      .each(Drupal.advanced_forum.bindCollapsible)
      .addClass('advanced-forum-collapsible-processed');
  }
})(jQuery);
