class pvpJsReplicateHtmlValue {
    observers = [];

    setElements(replicatedElements) {
        for (const elm of replicatedElements) {
            var source = document.querySelector(elm.source);

            if (null === source || 'undefined' == source) {
                return;
            }

            var config = {
                attributes: false,
                childList: true,
                subtree: false,
                characterData: true
            };



            var self = this;
            var callback = function () {
                self.replicate(elm);
            }

            self.replicate(elm);

            let index = this.observers.length;
            this.observers[index] = new MutationObserver(callback);
            this.observers[index].observe(source, config);
        }
    }

    replicate(params) {
      $(params.target).html($(params.source).html());
    };
}

const pvpJsReplicator = new pvpJsReplicateHtmlValue();


