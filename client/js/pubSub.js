export default function createPubSub() {
  let subscriptions = {};

  function on(event, callback) {
    const currentSubscriptions = getSubscriptions(event);

    subscriptions = {
      ...subscriptions,
      [event]: [...currentSubscriptions, callback]
    };
  }

  function emit(event, data) {
    getSubscriptions(event).forEach(callback => {
      callback(data);
    });
  }

  function getSubscriptions(event) {
    return subscriptions[event] || [];
  }

  return { on, emit };
}
