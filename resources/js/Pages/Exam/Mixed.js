import React from "react";

export const ExamPageTableHeader = ()=> {
    return (
        <tr>
            <th className="text-sm align-middle pl-2">Judul Ujian</th>
            <th width={100} className="text-sm align-middle">Soal</th>
            <th width={100} className="text-sm align-middle">PG</th>
            <th width={100} className="text-sm align-middle">Hasil</th>
            <th width={50} className="text-sm align-middle pr-2">Aksi</th>
        </tr>
    )
}
export const ExamPageCardHeader = ({...props})=> {
    return (
        <div className="card-header">
            <h1 className="card-title">Data Ujian</h1>
            <div className="card-tools">
                <button type="button" className="btn btn-sm btn-primary mr-1" onClick={()=>props.onForm()}>
                    <i className="fas fa-plus mr-1"/> Tambah
                </button>
                <button type="button" className="btn btn-sm btn-default" onClick={props.onReload}>
                    <i className="fas fa-xs fa-refresh"/>
                </button>
            </div>
        </div>
    )
}
export const ExamQuestionBadge = ({...props})=> {
    const options = {className: 'badge-secondary', label: 'UND'};
    if (typeof props.item !== "undefined" && props.item !== null) {
        if (props.item.meta.random.question) {
            options.className = 'badge-primary', options.label = 'Diacak';
        } else {
            options.className = 'badge-warning', options.label = 'Tidak Diacak';
        }
    }
    return <span className={`${options.className} text-xs px-3 py-2 d-block badge`}>{options.label}</span>
}
export const ExamAnswerBadge = ({...props})=> {
    const options = {className: 'badge-secondary', label: 'UND'};
    if (typeof props.item !== "undefined" && props.item !== null) {
        if (props.item.meta.random.answer) {
            options.className = 'badge-primary', options.label = 'Diacak';
        } else {
            options.className = 'badge-warning', options.label = 'Tidak Diacak';
        }
    }
    return <span className={`${options.className} text-xs px-3 py-2 d-block badge`}>{options.label}</span>
}
export const ExamResultBadge = ({...props})=> {
    const options = {className: 'badge-secondary', label: 'UND'};
    if (typeof props.item !== "undefined" && props.item !== null) {
        if (props.item.meta.show_result) {
            options.className = 'badge-primary', options.label = 'Diperlihatkan';
        } else {
            options.className = 'badge-warning', options.label = 'Tidak Diperlihatkan';
        }
    }
    return <span className={`${options.className} text-xs px-3 py-2 d-block badge`}>{options.label}</span>
}
