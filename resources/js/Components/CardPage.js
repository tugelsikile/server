import React from "react";

export const CardPageHeader = ({...props})=> {
    return (
        <div className="card-header">
            <h1 className="card-title">{typeof props.label === "undefined" ? 'Data' : props.label}</h1>
            <div className="card-tools">
                <button type="button" className="btn btn-sm btn-primary mr-1" onClick={()=>props.onForm()}>
                    <i className="fas fa-plus mr-1"/> Tambah
                </button>
                <button type="button" className="btn btn-sm btn-default" onClick={props.onReload}>
                    <i className="fas fa-2xs fa-refresh"/>
                </button>
            </div>
        </div>
    )
}
