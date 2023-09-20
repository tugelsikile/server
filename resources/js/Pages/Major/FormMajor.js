import React from "react";
import {toastError, toastException, toastSuccess} from "../../Components/Toaster";
import {crudMajor} from "../../Services/majorService";

class FormMajor extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            form: {
                id: null, name: '', code: '',
            }
        };
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }
    componentDidMount() {
        if (typeof this.props.open !== "undefined" && this.props.open) {
            if (typeof this.props.data !== "undefined" && this.props.data !== null) {
                if (this.state.form.id === null) {
                    let form = this.state.form;
                    form.id = this.props.data.value;
                    form.name = this.props.data.label;
                    form.code = this.props.data.meta.code;
                    this.setState({form});
                }
            }
        }
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
                if (this.state.form.id !== null) formData.append('data_jurusan', this.state.form.id);
                formData.append('nama_jurusan', this.state.form.name);
                formData.append('singkatan', this.state.form.code);
                let response = await crudMajor(formData);
                if (response.data.status_data === null) {
                    this.setState({loading:false});
                    toastError(response.data.status_message);
                } else {
                    this.setState({loading:false});
                    toastSuccess(response.data.status_message);
                    this.props.handleUpdate(response.data.status_data);
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
            <form onSubmit={this.handleSubmit} className={`card card-outline ${this.state.form.id === null ? 'card-primary' : 'card-info'}`}>
                <div className="card-header">
                    <h1 className="card-title">Formulir {this.state.form.id === null ? 'Tambah' : 'Rubah'} Jurusan</h1>
                </div>
                <div className="card-body">
                    <div className="form-group row">
                        <label className="col-form-label col-md-3">Nama Jurusan</label>
                        <div className="col-md-9">
                            <input value={this.state.form.name} name="name" onChange={this.handleChange} className="form-control" disabled={this.state.loading}/>
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-form-label col-md-3">Singkatan</label>
                        <div className="col-md-9">
                            <input value={this.state.form.code} name="code" onChange={this.handleChange} className="form-control" disabled={this.state.loading}/>
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
        );
    }
}
export default FormMajor;
