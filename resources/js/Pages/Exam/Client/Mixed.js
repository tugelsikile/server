import React from "react";

export const ClientPageCardHeader = ({...props})=> {
    return (
        <div className="card-header">
            <h1 className="card-title">Data Server Client Ujian</h1>
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
export const ClientTableHeader = ()=> {
    return (
        <tr>
            <th width={100} className="align-middle pl-2">ID Server</th>
            <th className="align-middle">Nama Server</th>
            <th className="align-middle">Ujian</th>
            <th className="align-middle">Token</th>
            <th width={50} className="align-middle pr-2">Aksi</th>
        </tr>
    )
}
