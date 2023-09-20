import Axios from "axios";
import {axiosHeader} from "./config";

export const crudMajor = async (data)=> {
    let request = Axios({
        headers : axiosHeader(),
        method : "post", data : data, url : `${window.origin}/api/major`
    });
    return Promise.resolve(request);
}
