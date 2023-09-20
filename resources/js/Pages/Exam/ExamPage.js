import React from "react";
import ReactDOM from "react-dom/client";
import {checkAuth} from "../../Components/Authentication";
import {HeaderAndSideBar, MainFooter, PageHeader} from "../../Components/Layout";
import {toastConfirmation, toastError, toastException} from "../../Components/Toaster";
import {crudExam} from "../../Services/examService";
import {ExamAnswerBadge, ExamPageCardHeader, ExamPageTableHeader, ExamQuestionBadge, ExamResultBadge} from "./Mixed";
import FormExam from "./FormExam";

class ExamPage extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            user: JSON.parse(localStorage.getItem('user')),
            exams: { loading: false, data: [] },
            form: { open: false, data: null },
        }; //state itu variabel yang disimpan dan nanti akan digunakan
        this.toggleForm = this.toggleForm.bind(this); //definisikan disini dulu methodnya biar nanti bisa punya akses ke state yang diatas
        this.loadExam = this.loadExam.bind(this);
        this.confirmDelete = this.confirmDelete.bind(this);
    }
    componentDidMount() {//fungsi pada saat pertama kali jalan
        checkAuth()
            .then(()=>this.loadExam()); //method chain setelah checkAuth selesai, maka akan menjalankan method loadExam
    }
    confirmDelete(data){
        if (data !== null) {
            const options = {
                data: data.value,
                app: this, method: 'delete', icon: 'error',
                inputName: 'data_ujian', url: `${window.origin}/api/exam`,
                title:'Hapus Ujian', message : `Anda yakin ingin menghapus Data Ujian <strong>${data.label}</strong>?<br/>Data yang berhubungan akan ikut dihapus.<br/>Lanjutkan hapus data ujian?`,
                callback: 'props.app.loadExam()'
            };
            toastConfirmation(options);
        }
    }
    toggleForm(data = null) {
        let form = this.state.form;
        form.open = ! this.state.form.open;
        form.data = data;
        this.setState({form});
    }
    async loadExam() {
        if (! this.state.exams.loading) {
            let exams = this.state.exams;
            exams.loading = true;
            this.setState({exams});
            try {
                let response = await crudExam();
                if (response.data.status_data === null) {
                    exams.loading = false; this.setState({exams});
                    toastError(response.data.status_message);
                } else {
                    exams.loading = false;
                    exams.data = response.data.status_data;
                    this.setState({exams});
                }
            } catch (e) {
                exams.loading = false; this.setState({exams});
                toastException(e);
            }
        }
    }
    render() {
        return (
            <React.StrictMode>
                <HeaderAndSideBar route={this.props.route} {...this.state}/>
                <div className="content-wrapper">
                    <PageHeader title="Ujian" childrens={[]}/>
                    <section className="content">
                        {this.state.form.open ?
                            <FormExam open={this.state.form.open}
                                      data={this.state.form.data}
                                      handleUpdate={this.loadExam}
                                      handleClose={this.toggleForm}/>
                            :
                            <div className="card card-outline card-primary">
                                {this.state.exams.loading && <div className="overlay"></div> }
                                <ExamPageCardHeader onForm={this.toggleForm} onReload={this.loadExam}/>
                                <div className="card-body p-0">
                                    <div className="table-responsive">
                                        <table className="table mb-0 table-sm table-borderless table-striped table-hover">
                                            <thead>
                                                <ExamPageTableHeader/>
                                            </thead>
                                            <tbody>
                                            {this.state.exams.data.length === 0 ?
                                                <tr>
                                                    <td colSpan={6} className="align-middle text-center">
                                                        {this.state.exams.loading ? 'Memuat data' : 'Data tidak ditemukan'}
                                                    </td>
                                                </tr>
                                                :
                                                this.state.exams.data.map((item)=>
                                                    <tr onDoubleClick={()=>this.toggleForm(item)} key={item.value}>
                                                        <td className="align-middle pl-2">{item.label}</td>
                                                        <td className="align-middle">{ExamQuestionBadge({item:item})}</td>
                                                        <td className="align-middle">{ExamAnswerBadge({item:item})}</td>
                                                        <td className="align-middle">{ExamResultBadge({item:item})}</td>
                                                        <td className="align-middle pr-2">
                                                            <div className="btn-group btn-group-sm">
                                                                <button onClick={()=>this.toggleForm(item)} type="button" className="btn btn-primary"><i className="fas fa-2xs fa-pencil-alt"/></button>
                                                                <button onClick={()=>this.confirmDelete(item)} type="button" className="btn btn-danger"><i className="fas fa-2xs fa-trash-alt"/></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                )
                                            }
                                            </tbody>
                                            <tfoot>
                                                <ExamPageTableHeader/>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        }
                    </section>
                </div>
                <MainFooter/>
            </React.StrictMode>
        )
    }
}
export default ExamPage;
const root = ReactDOM.createRoot(document.getElementById('main-container'));
root.render(<React.StrictMode><ExamPage route="exam"/></React.StrictMode>)
