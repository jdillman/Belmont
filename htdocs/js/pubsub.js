/**
 * Simple JS pubsub.
 *
 * @example
 * 
 * // Set up the subscription
 * PS.on("modal:show", function (data) { });
 *
 * // Set up a 1 time subscriber
 * PS.once("user:authenticated", function (data) { });
 * 
 * // Publish a message. Triggering the subscriptions in first in last out order
 * PS.fire("modal:show", data);
 *
 * // unsubscribe via the token returned via subscribe() or on()
 * PS.unsubscribe("modal:show", token);
 * 
 * // Unsubscribe ALL listeners
 * PS.unsubscribe("modal:*");
 * 
 * @API
 * publish(message, args)
 * subscribe(message, callback, options)
 * unsubscribe(message, callback)
 * once(message, callback)
 * fire(message, args) // Alias of publish
 * on(message, callback, options) // Alias of subscribe
 * 
 */
(function (ctx) {
 
  // currently active subscriptions
  var subscriptions = {};
 
  // Attaches the methods on either the passed in context 
  // or creates a window.PS global
  if (!ctx) {
    ctx = window.PS = {};
  }
 
  // Sprinkle some sugar on it
  ctx.on = ctx.subscribe = subscribe;
  ctx.fire = ctx.publish = publish;
  ctx.unsubscribe = unsubscribe;
  ctx.once = once;
 
  function subscribe (message, cb, opts) {
 
    // break arrays of messages down and subscribe each item
    if (typeof message !== "string" && message.length) {
      for (var x = 0; x < message.length; x++) {
        subscribe(message[x], cb, opts);
      }
    }
 
    // Defaults
    opts = opts || {};
    var priority = opts.priority || 100;
    var count = opts.count || -1;
 
    // If you want a different context in your subscription use .apply
    var sub = {
      cb: cb,
      context: this,
      priority: priority,
      count: count
    };
 
    if (!subscriptions[message]) {
      subscriptions[message] = [];
    }
 
    // Put the new event on the bottom, events go first in last out
    // TOOD priority
    subscriptions[message].unshift(sub);
    
    // Identify the subscription based on the cb function being the same
    return cb;
  };
 
  function publish (message) {
    if (!subscriptions[message]) {
      return false;
    }
 
    var args = Array.prototype.slice.call(arguments, 1);
    var subs = subscriptions[message].slice();
 
    var ret = true;
    for (var x = 0; x < subs.length; x++) {
      subs[x].count--;
      if (subs[x].count === 0) {
        unsubscribe(message, subscriptions[message][x].cb);
      }
      ret = subs[x].cb.apply(subs[x].context, args);
      // Keep the explicity comparison. !ret will match undefined
      if (ret === false) {
        break;
      }
    }
 
    // return false if a callback explicity called false to stop the message
    return (typeof ret !== "undefined") ? ret : true;
  }
 
  function unsubscribe (message, cb) {
    if (!subscriptions[message]) {
      return;
    }
 
    if (typeof cb !== "undefined") {
      subscriptions[message] = [];
      return true;
    }
 
    var len = subscriptions[message].length;
    // Find the right sub to remove
    for (var x = 0; x < len; x++) {
      if (subscriptions[message][x].cb === cb) {
        subscriptions[message].splice(x, 1);
        break;
      }
    }
    return true;
  }
 
  // Sugar for a single exection subscription
  function once (message, cb, opts) {
    opts = opts || {};
    opts.count = 1;
    return subscribe(message, cb, opts);
  }
 
}());
