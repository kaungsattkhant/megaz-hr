import { useToast } from "vue-toastification";

const toast = useToast();

export function notify(msg, type)
{
    /* type can be one of [info, success, error, warning] */
    toast[type](msg, {
        timeout: 2000
    });
}
