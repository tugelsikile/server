import React from "react";
import ReactDOM from "react-dom/client";
import {checkAuth} from "../../Components/Authentication";
import {toastConfirmation, toastError, toastException} from "../../Components/Toaster";
import {crudCourse} from "../../Services/courseService";
import {HeaderAndSideBar, MainFooter, PageHeader} from "../../Components/Layout";
import {crudMajor} from "../../Services/majorService";
import FormCourse from "./FormCourse";
import {CardPageHeader} from "../../Components/CardPage";

class CoursePage extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            user: JSON.parse(localStorage.getItem('user')),
            courses: { loading: true, data: [] },
            majors: { loading: true, data : [] },
            form: { open: false, data: null },
        };
        this.toggleForm = this.toggleForm.bind(this);
        this.loadCourse = this.loadCourse.bind(this);
        this.loadMajor = this.loadMajor.bind(this);
        this.confirmDelete = this.confirmDelete.bind(this);
    }
    componentDidMount() {
        checkAuth()
            .then(()=>{
                let courses = this.state.courses; courses.loading = false; this.setState({courses},()=>this.loadCourse());
            })
            .then(()=>{
                let majors = this.state.majors; majors.loading = false; this.setState({majors},()=>this.loadMajor());
            })
    }
    confirmDelete(data){
        if (data !== null) {
            const options = {
                data: data.value,
                app: this, method: 'delete', icon: 'error',
                inputName: 'data_mata_pelajaran', url: `${window.origin}/api/course`,
                title:'Hapus Mata Pelajaran', message : `Anda yakin ingin menghapus Data Mata Pelajaran <strong>${data.label}</strong>?<br/>Data yang berhubungan akan ikut dihapus.<br/>Lanjutkan hapus data mata pelajaran?`,
                callback: 'props.app.loadCourse()'
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
    async loadMajor() {
        if (! this.state.majors.loading) {
            let majors = this.state.majors;
            majors.loading = true;
            this.setState({majors});
            try {
                let response = await crudMajor();
                if (response.data.status_data === null) {
                    majors.loading = false; this.setState({majors});
                    toastError(response.data.status_message);
                } else {
                    majors.loading = false;
                    majors.data = response.data.status_data;
                    this.setState({majors});
                }
            } catch (e) {
                majors.loading = false; this.setState({majors});
                toastException(e);
            }
        }
    }
    async loadCourse() {
        if (! this.state.courses.loading) {
            let courses = this.state.courses;
            courses.loading = true;
            this.setState({courses});
            try {
                let response = await crudCourse();
                if (response.data.status_data === null) {
                    courses.loading = false; this.setState({courses});
                    toastError(response.data.status_message);
                } else {
                    courses.loading = false;
                    courses.data = response.data.status_data;
                    this.setState({courses});
                }
            } catch (e) {
                courses.loading = false; this.setState({courses});
                toastException(e);
            }
        }
    }
    render() {
        return (
            <React.StrictMode>
                <HeaderAndSideBar route={this.props.route} {...this.state}/>
                <div className="content-wrapper">
                    <PageHeader title="Mata Pelajaran" children={[]}/>
                    <section className="content">
                        {this.state.form.open ?
                            <FormCourse open={this.state.form.open}
                                        data={this.state.form.data}
                                        handleClose={this.toggleForm}
                                        handleUpdate={this.loadCourse}
                                        handleMajor={this.loadMajor}
                                        majors={this.state.majors}/>
                            :
                            <div className="card card-outline card-primary">
                                {this.state.courses.loading && <div className="overlay"></div> }
                                <CardPageHeader title="Data Mata Pelajaran" onForm={this.toggleForm} onReload={this.loadCourse}/>
                                <div className="card-body p-0">
                                    <div className="table-responsive">
                                        <table className="table table-sm table-borderless table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th className="align-middle pl-2">Nama Mata Pelajaran</th>
                                                <th className="align-middle">Singkatan</th>
                                                <th className="align-middle">Jurusan</th>
                                                <th width={100} className="align-middle">Tingkat</th>
                                                <th width={30} className="align-middle pr-2">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {this.state.courses.data.length === 0 ?
                                                <tr>
                                                    <td colSpan={5} className="align-middle text-center">
                                                        {this.state.courses.loading ? 'Memuat data ...' : 'Data tidak ditemukan'}
                                                    </td>
                                                </tr>
                                                :
                                                this.state.courses.data.map((item)=>
                                                    <tr key={item.value}>
                                                        <td className="align-middle pl-2">{item.label}</td>
                                                        <td className="align-middle">{item.meta.code}</td>
                                                        <td className="align-middle">{item.meta.major !== null && item.meta.major.label}</td>
                                                        <td className="align-middle">{item.meta.level}</td>
                                                        <td className="align-middle pr-2">
                                                            <div className="btn-group btn-group-sm">
                                                                <button type="button" className="btn btn-primary" onClick={()=>this.toggleForm(item)}>
                                                                    <i className="fas fa-pencil-alt fa-2xs"/>
                                                                </button>
                                                                <button type="button" className="btn btn-danger" onClick={()=>this.confirmDelete(item)}>
                                                                    <i className="fas fa-trash-alt fa-2xs"/>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                )
                                            }
                                            </tbody>
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
export default CoursePage;
const root = ReactDOM.createRoot(document.getElementById('main-container'));
root.render(<React.StrictMode><CoursePage route="course"/></React.StrictMode>);
