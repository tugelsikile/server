import Axios from "axios";
import {axiosHeader} from "./config";

export const crudExam = async (data)=> {
    let request = Axios({
        headers : axiosHeader(),
        method : "post", data : data, url : `${window.origin}/api/exam`
    });
    return Promise.resolve(request);
}
export const crudClient = async (data)=> {
    let request = Axios({
        headers : axiosHeader(),
        method : "post", data : data, url : `${window.origin}/api/exam/client`
    });
    return Promise.resolve(request);
}
