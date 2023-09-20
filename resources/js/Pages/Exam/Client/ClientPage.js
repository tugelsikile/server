import React from "react";
import ReactDOM from "react-dom/client";
import {checkAuth} from "../../../Components/Authentication";
import {toastConfirmation, toastError, toastException} from "../../../Components/Toaster";
import {crudClient, crudExam} from "../../../Services/examService";
import {HeaderAndSideBar, MainFooter, PageHeader} from "../../../Components/Layout";
import FormClient from "./FormClient";
import {ClientPageCardHeader, ClientTableHeader} from "./Mixed";

class ClientPage extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            user: JSON.parse(localStorage.getItem('user')),
            exams: { loading: true, data: [] },
            clients : { loading: true, data : [] },
            form: { open: false, data: null },
        };
        this.toggleForm = this.toggleForm.bind(this);
        this.loadExam = this.loadExam.bind(this);
        this.loadClient = this.loadClient.bind(this);
        this.confirmDelete = this.confirmDelete.bind(this);
    }
    componentDidMount() {
        checkAuth()
            .then(()=>{
                let exams = this.state.exams; exams.loading = false; this.setState({exams},()=>this.loadExam());
            })
            .then(()=>{
                let clients = this.state.clients; clients.loading = false; this.setState({clients},()=>this.loadClient());
            })
    }
    confirmDelete(data){
        if (data !== null) {
            const options = {
                data: data.value,
                app: this, method: 'delete', icon: 'error',
                inputName: 'data_client', url: `${window.origin}/api/exam/client`,
                title:'Hapus Server Client Ujian', message : `Anda yakin ingin menghapus Data Server Client Ujian <strong>${data.label}</strong>?<br/>Data yang berhubungan akan ikut dihapus.<br/>Lanjutkan hapus data server client ujian?`,
                callback: 'props.app.loadClient()'
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
    async loadClient() {
        if (! this.state.clients.loading) {
            let clients = this.state.clients;
            clients.loading = true;
            this.setState({clients});
            try {
                let response = await crudClient();
                if (response.data.status_data === null) {
                    clients.loading = false; this.setState({clients});
                    toastError(response.data.status_message);
                } else {
                    clients.loading = false;
                    clients.data = response.data.status_data;
                    this.setState({clients});
                }
            } catch (e) {
                clients.loading = false; this.setState({clients});
                toastException(e);
            }
        }
    }
    render() {
        return (
            <React.StrictMode>
                <HeaderAndSideBar route={this.props.route} {...this.state}/>
                <div className="content-wrapper">
                    <PageHeader title="Server Client Ujian" children={[
                        {url: `${window.origin}/exam`, label: 'Ujian'}
                    ]}/>
                    <section className="content">
                        {this.state.form.open ?
                            <FormClient open={this.state.form.open}
                                        data={this.state.form.data}
                                        exams={this.state.exams}
                                        handleExam={this.loadExam}
                                        handleClose={this.toggleForm}
                                        handleUpdate={this.loadClient}/>
                            :
                            <div className="card card-outline card-primary">
                                {this.state.clients.loading && <div className="overlay"></div> }
                                <ClientPageCardHeader onForm={this.toggleForm} onReload={this.loadClient}/>
                                <div className="card-body p-0">
                                    <div className="table-responsive">
                                        <table className="table mb-0 table-sm table-borderless table-striped table-hover">
                                            <thead>
                                                <ClientTableHeader/>
                                            </thead>
                                            <tbody>
                                            {this.state.clients.data.length === 0 ?
                                                <tr>
                                                    <td colSpan={5} className="align-middle text-center">
                                                        {this.state.clients.loading ? 'Memuat data ...' : 'Data tidak ditemukan'}
                                                    </td>
                                                </tr>
                                                :
                                                this.state.clients.data.map((item)=>
                                                    <tr onDoubleClick={()=>this.toggleForm(item)} key={item.value}>
                                                        <td className="align-middle pl-2">{item.meta.code}</td>
                                                        <td className="align-middle">{item.label}</td>
                                                        <td className="align-middle">{item.meta.exam.label}</td>
                                                        <td className="align-middle">{item.meta.token}</td>
                                                        <td className="align-middle pr-2">
                                                            <div className="btn-group btn-group-sm">
                                                                <button type="button" className="btn btn-primary" onClick={()=>this.toggleForm(item)}>
                                                                    <i className="fas fa-2xs fa-pencil-alt"/>
                                                                </button>
                                                                <button type="button" className="btn btn-danger" onClick={()=>this.confirmDelete(item)}>
                                                                    <i className="fas fa-2xs fa-trash-alt"/>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                )
                                            }
                                            </tbody>
                                            <tfoot>
                                                <ClientTableHeader/>
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
export default ClientPage;
const root = ReactDOM.createRoot(document.getElementById('main-container'));
root.render(<React.StrictMode><ClientPage route="client"/></React.StrictMode>);
