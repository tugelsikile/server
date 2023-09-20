import React from "react";
import {toast} from "react-toastify";
import {logout} from "./Authentication";
import Swal from "sweetalert2";
import Axios from "axios";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faExclamationTriangle, faInfoCircle} from "@fortawesome/free-solid-svg-icons";
import {axiosHeader} from "../Services/config";

const normalizeMessage = (messages)=> {
    messages = messages.split("\n");
    return (
        <ul className="list-unstyled mb-0">
            {messages.map((message,indexMessage)=>
                <li key={`toastMessage${indexMessage}`}>
                    {message}
                </li>
            )}
        </ul>
    )
}
export const toastWarning = (message, options = null)=> {
    const option = {
        icon : <FontAwesomeIcon icon={faInfoCircle} className="text-warning"/>,
        bodyClassName: 'text-xs',
    };
    toast.warning(normalizeMessage(message), option);
}
export const toastError = (message, options = null) => {
    const option = {
        icon : <FontAwesomeIcon icon={faExclamationTriangle} className="text-danger"/>,
        bodyClassName: 'text-xs',
    };
    toast.error(normalizeMessage(message), {...options,...option});
}
export const toastSuccess = (message, options = null)=> {
    const option = {...options};
    toast.success(normalizeMessage(message), option);
}
export const toastException = (error, showToast = true)=> {
    let message = 'Undefined error';
    if (typeof error.response !== "undefined") {
        if (typeof error.response.status !== "undefined") {
            message = "request successfully loaded with an error";
            if (typeof error.response.data !== "undefined") {
                if (error.response.data !== null) {
                    if (typeof error.response.data.status_message !== "undefined") message = error.response.data.status_message;
                }
            }
            if (error.response.status === 404) message = "Page not found";
            if (error.response.status === 401) {
                message = "Unauthenticated";
                logout();
            }
        }
    }
    if (showToast) {
        toastError(message);
    } else {
        return message;
    }
}
export const toastConfirmation = (props)=> {
    const options = {
        message: 'Anda yakin ingin melakukan aksi ini?',
        title: 'Perhatian',
        icon: 'question',
        confirmButton: { Color: '#E04F1A', Text: 'Lanjut' },
        cancelButton: { Color: '#3C90DF', Text: 'Batal' },
        url: `${window.origin}/api`,
        inputName: 'id',
    };
    if (typeof props.message !== "undefined") options.message = props.message.replaceAll("\n","<br/>");
    if (typeof props.title !== "undefined") options.title = props.title;
    if (typeof props.icon !== "undefined") options.icon = props.icon;
    if (typeof props.confirmButtonColor !== "undefined") options.confirmButton.Color = props.confirmButtonColor;
    if (typeof props.confirmButtonText !== "undefined") options.confirmButton.Text = props.confirmButtonText;
    if (typeof props.cancelButtonText !== "undefined") options.cancelButton.Text = props.cancelButtonText;
    if (typeof props.url !== "undefined") options.url = props.url;
    if (typeof props.inputName !== "undefined") options.inputName = props.inputName;
    const formData = new FormData();
    if (typeof props.additional_input !== "undefined") {
        if (Array.isArray(props.additional_input)) {
            props.additional_input.map((item)=>{
                if (typeof item.value !== "undefined") {
                    let inputName = 'additional_input';
                    if (typeof item.inputName !== "undefined") inputName = item.inputName;
                    formData.append(inputName, item.value);
                }
            });
        }
    }
    if (typeof props.method !== "undefined") formData.append('_method', props.method);
    if (typeof props.data !== "undefined") {
        if (props.data !== null) {
            if (Array.isArray(props.data)) {
                props.data.map((item,idx)=>{
                    formData.append(`${options.inputName}[${idx}]`, item);
                });
            } else if (typeof props.data === "string") {
                formData.append(options.inputName, props.data);
            }
        }
    }
    Swal.fire({
        title: options.title, html: options.message, icon: options.icon,
        showCancelButton: true,
        confirmButtonColor: options.confirmButton.Color, confirmButtonText: options.confirmButton.Text,
        cancelButtonText: options.cancelButton.Text, cancelButtonColor: options.cancelButton.Color,
        closeOnConfirm : false,
        showLoaderOnConfirm: true, allowOutsideClick: () => ! Swal.isLoading(), allowEscapeKey : () => ! Swal.isLoading(),
        preConfirm : ()=> {
            return Promise.resolve(Axios({
                headers : axiosHeader(),
                method : 'post',
                data : formData,
                url : options.url
            }))
                .then((response) => {
                    if (response.data.params === null){
                        Swal.showValidationMessage(response.data.status_message);
                        Swal.hideLoading();
                    } else {
                        Swal.close();
                        toastSuccess(response.data.status_message);
                        if (typeof props.callback !== "undefined") {
                            eval(props.callback);
                        }
                    }
                }).catch((error)=>{
                    Swal.showValidationMessage(error.response.data.status_message);
                });
        }
    }).then();
}
