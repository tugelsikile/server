import React from "react";
import {toastError, toastException, toastSuccess} from "../../../Components/Toaster";
import {crudClient} from "../../../Services/examService";
import FormExam from "../FormExam";

class FormClient extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            form: {
                id: null, exam: '', name: '',
            },
            form_exam: { open: false, data: null }
        };
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.toggleForm = this.toggleForm.bind(this);
        this.handleUpdateExam = this.handleUpdateExam.bind(this);
    }
    toggleForm(data = null) {
        let form_exam = this.state.form_exam;
        form_exam.open = ! this.state.form_exam.open;
        form_exam.data = data;
        this.setState({form_exam});
    }
    handleUpdateExam(data) {
        let form = this.state.form;
        form.exam = data.value;
        this.setState({form});
        this.props.handleExam(data);
    }
    componentDidMount() {
        let form = this.state.form;
        if (typeof this.props.open !== "undefined" && this.props.open) {
            if (typeof this.props.data !== "undefined" && this.props.data !== null) {
                if (form.id === null) {
                    form.id = this.props.data.value;
                    form.exam = this.props.data.meta.exam.value;
                    form.name = this.props.data.label;
                }
            }
        }
        this.setState({form});
    }
    handleChange(event) {
        let form = this.state.form;
        form[event.target.name] = event.target.value;
        this.setState({form});
    }
    async handleSubmit(event) {
        event.preventDefault();
        if (! this.state.loading) {
            this.setState({loading:true});
            try {
                const formData = new FormData();
                formData.append('_method', this.state.form.id === null ? 'put' : 'patch');
                if (this.state.form.id !== null) formData.append('data_client', this.state.form.id);
                if (this.state.form.exam.length > 0) formData.append('ujian', this.state.form.exam);
                formData.append('nama_client', this.state.form.name);
                let response = await crudClient(formData);
                if (response.data.params === null) {
                    this.setState({loading:false});
                    toastError(response.data.status_message);
                } else {
                    this.setState({loading:false});
                    toastSuccess(response.data.status_message);
                    this.props.handleUpdate();
                    this.props.handleClose();
                }
            } catch (e) {
                this.setState({loading:false});
                toastException(e);
            }
        }
    }
    render() {
        return (
            this.state.form_exam.open ?
                <FormExam
                    data={this.state.form_exam.data} handleClose={this.toggleForm}
                    open={this.state.form_exam.open} handleUpdate={this.handleUpdateExam}/>
                :
                <form onSubmit={this.handleSubmit} className={`card card-outline ${this.state.form.id === null ? 'card-primary' : 'card-info'}`}>
                <div className="card-header">
                    <h1 className="card-title">Formulir {this.state.form.id === null ? 'Tambah' : 'Rubah'} Server Client Ujian</h1>
                </div>
                <div className="card-body">
                    <div className="form-group row">
                        <label className="col-form-label col-md-3">Ujian</label>
                        <div className="col-md-7">
                            <select onChange={this.handleChange} name="exam" value={this.state.form.exam} className="custom-select" disabled={this.state.loading}>
                                <option value="">=== Pilih Ujian ===</option>
                                {this.props.exams.data.map((item)=>
                                    <option key={item.value} value={item.value}>{item.label}</option>
                                )}
                            </select>
                        </div>
                        <div className="col-md-2">
                            <button title="Buat data ujian baru" disabled={this.state.loading} type="button" className="btn btn-default btn-sm mr-1" onClick={()=>this.toggleForm()}>
                                <i className="fas fa-2xs fa-plus"/>
                            </button>
                            {this.state.form.exam.length > 0 &&
                                <button title="Rubah data ujian ini" disabled={this.state.loading} type="button" className="btn btn-primary btn-sm" onClick={()=> {
                                    let index = this.props.exams.data.findIndex((f)=> f.value === this.state.form.exam);
                                    if (index >= 0) {
                                        this.toggleForm(this.props.exams.data[index]);
                                    }
                                }}>
                                    <i className="fas fa-2xs fa-pencil-alt"/>
                                </button>
                            }
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-form-label col-md-3">Nama Server</label>
                        <div className="col-md-9">
                            <input value={this.state.form.name} name="name" onChange={this.handleChange} className="form-control" disabled={this.state.loading}/>
                        </div>
                    </div>
                </div>
                <div className="card-footer">
                    <div className="d-flex justify-content-between">
                        <button type="submit" className="btn btn-primary">
                            <i className={`fas mr-1 ${this.state.loading ? 'fa-circle-notch fa-spin' : 'fa-save'}`}/>
                            {this.state.form.id === null ? 'Tambah' : 'Simpan'}
                        </button>
                        <button onClick={()=>this.props.handleClose()} type="button" className="btn btn-default">
                            <i className="fas fa-times mr-1"/> Tutup
                        </button>
                    </div>
                </div>
            </form>
        )
    }
}
export default FormClient;
