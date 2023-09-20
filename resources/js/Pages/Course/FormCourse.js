import React from "react";
import {toastError, toastException, toastSuccess} from "../../Components/Toaster";
import {crudCourse} from "../../Services/courseService";
import FormMajor from "../Major/FormMajor";

class FormCourse extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            form: {
                id: null, name: '', major: '', code: '', level: 10,
            },
            form_major: { open: false, data : null },
        };
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.toggleForm = this.toggleForm.bind(this);
        this.handleMajor = this.handleMajor.bind(this);
    }
    componentDidMount() {
        if (typeof this.props.open !== "undefined" && this.props.open) {
            if (typeof this.props.data !== "undefined" && this.props.data !== null) {
                if (this.state.form.id === null) {
                    let form = this.state.form;
                    form.id = this.props.data.value;
                    form.name = this.props.data.label;
                    if (this.props.data.meta.major !== null) form.major = this.props.data.meta.major.value;
                    form.code = this.props.data.meta.code;
                    form.level = this.props.data.meta.level;
                    this.setState({form});
                }
            }
        }
    }
    toggleForm(data = null) {
        let form_major = this.state.form_major;
        form_major.open = ! this.state.form_major.open;
        form_major.data = data;
        this.setState({form_major});
    }
    handleMajor(data) {
        let form = this.state.form;
        form.major = data.value;
        this.setState({form});
        this.props.handleMajor(data);
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
                if (this.state.form.id !== null) formData.append('data_mata_pelajaran', this.state.form.id);
                if (this.state.form.major.length > 0) formData.append('jurusan', this.state.form.major);
                formData.append('singkatan', this.state.form.code);
                formData.append('nama_mata_pelajaran', this.state.form.name);
                formData.append('tingkat', this.state.form.level);
                let response = await crudCourse(formData);
                if (response.data.status_data === null) {
                    this.setState({loading:false});
                    toastError(response.data.status_message);
                } else {
                    this.setState({loading:false});
                    toastSuccess(response.data.status_message);
                    this.props.handleUpdate();
                    this.props.handleClose();
                }
            } catch (error) {
                this.setState({loading:false});
                toastException(error);
            }
        }
    }
    render() {
        return (
            this.state.form_major.open ?
                <FormMajor open={this.state.form_major.open} data={this.state.form_major.data} handleClose={this.toggleForm} handleUpdate={this.handleMajor}/>
                :
                <form onSubmit={this.handleSubmit} className={`card card-outline ${this.state.form.id === null ? 'card-primary' : 'card-info'}`}>
                    <div className="card-header">
                        <h1 className="card-title">Formulir {this.state.form.id === null ? 'Tambah' : 'Rubah'} Mata Pelajaran</h1>
                    </div>
                    <div className="card-body">
                        <div className="form-group row">
                            <label className="col-form-label col-md-3">Jurusan</label>
                            <div className="col-md-7">
                                <select value={this.state.form.major} onChange={this.handleChange} name="major" className="custom-select" disabled={this.state.loading || this.props.majors.loading}>
                                    <option value="">=== Pilih Jurusan ===</option>
                                    {this.props.majors.data.map((item)=>
                                        <option key={item.value} value={item.value}>{item.label}</option>
                                    )}
                                </select>
                            </div>
                            <div className="col-md-2">
                                <button disabled={this.state.loading} type="button" className="btn btn-sm btn-default mr-1" onClick={()=>this.toggleForm()}>
                                    <i className="fas fa-plus fa-2xs"/>
                                </button>
                                {this.state.form.major.length > 0 &&
                                    <button disabled={this.state.loading} type="button" className="btn btn-sm btn-primary" onClick={()=>{
                                        let index = this.props.majors.data.findIndex((f)=> f.value === this.state.form.major);
                                        if (index >= 0) this.toggleForm(this.props.majors.data[index]);
                                    }}>
                                        <i className="fas fa-pencil-alt fa-2xs"/>
                                    </button>
                                }
                            </div>
                        </div>
                        <div className="form-group row">
                            <label className="col-form-label col-md-3">Nama Mata Pelajaran</label>
                            <div className="col-md-9">
                                <input value={this.state.form.name} name="name" onChange={this.handleChange} className="form-control" disabled={this.state.loading}/>
                            </div>
                        </div>
                        <div className="form-group row">
                            <label className="col-form-label col-md-3">Singkatan</label>
                            <div className="col-md-4">
                                <input value={this.state.form.code} name="code" onChange={this.handleChange} className="form-control" disabled={this.state.loading}/>
                            </div>
                        </div>
                        <div className="form-group row">
                            <label className="col-form-label col-md-3">Tingkat</label>
                            <div className="col-md-4">
                                <input value={this.state.form.level} name="level" onChange={this.handleChange} className="form-control" disabled={this.state.loading} type="number" min={1} max={12}/>
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
export default FormCourse;
