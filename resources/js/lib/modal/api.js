import { h, render } from "vue";
import { eventBus } from "~/plugins";
import DialogComponent from "./components/dialog.vue";
import ConfirmSlotComponent from "./components/confirm.vue";

export const useDialog = (app = {}, globalProps = {}) => {
  return {
    component(slots = {}) {
      const vNode = h(DialogComponent, globalProps, slots);

      vNode.appContext = app?._context;

      render(vNode, document.body);

      return vNode.component;
    },
    open(options) {
      const i = this.component(options.slots);
      i.ctx.open(options);
    },
    confirm(options) {
      const i = this.component();
      i.ctx.confirm(options);
    },
    close() {
      eventBus.emit("close-dialog");
    },
  };
};
