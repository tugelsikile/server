import React from "react";
import {toastError, toastException, toastSuccess} from "../../Components/Toaster";
import {crudExam} from "../../Services/examService";

class FormExam extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            form: {
                id: null, name: '', description: '', answer: false, question: false, result: false,
            }
        };
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleSwitch = this.handleSwitch.bind(this);
    }
    /* method pada saat formulir dimuat*/
    componentDidMount() {
        let form = this.state.form;
        if (typeof this.props.open !== "undefined" && this.props.open) {
            if (typeof this.props.data !== "undefined" && this.props.data !== null) {
                if (form.id === null) {
                    /* isi formulir berdasarkan data dari ExamPage */
                    form.id = this.props.data.value;
                    form.name = this.props.data.label;
                    form.description = this.props.data.meta.description;
                    form.answer = this.props.data.meta.random.answer;
                    form.question = this.props.data.meta.random.question;
                    form.result = this.props.data.meta.show_result;
                }
            }
        }
        this.setState({form});
    }
    /* method pada saat user mengisi form input text atau textarea*/
    handleChange(event) {
        let form = this.state.form;
        form[event.target.name] = event.target.value;
        this.setState({form});
    }
    /* method pada saat user click checkbox / switch*/
    handleSwitch(event){
        let form = this.state.form;
        form[event.target.name] = ! this.state.form[event.target.name];
        this.setState({form});
    }
    /* method pada saat formulir disubmit */
    async handleSubmit(event){
        event.preventDefault();
        if (! this.state.loading) {
            this.setState({loading:true});
            try {
                const formData = new FormData();
                formData.append('_method', this.state.form.id === null ? 'put' : 'patch'); //method create atau update
                if (this.state.form.id !== null) formData.append('data_ujian', this.state.form.id);
                formData.append('nama_ujian', this.state.form.name);
                formData.append('keterangan', this.state.form.description);
                formData.append('acak_soal', this.state.form.question ? 'ya' : 'tidak');
                formData.append('acak_pilihan_ganda', this.state.form.answer ? 'ya' : 'tidak');
                formData.append('tampilkan_hasil', this.state.form.result ? 'ya' : 'tidak');
                let response = await crudExam(formData);
                if (response.data.status_data === null) {
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
            <form onSubmit={this.handleSubmit}>
                <div className={`card card-outline ${this.state.form.id === null ? 'card-primary' : 'card-info'}`}>
                    <div className="card-header">
                        <h1 className="card-title">Formulir {this.state.form.id === null ? 'Tambah' : 'Rubah'} Ujian</h1>
                    </div>
                    <div className="card-body">
                        <div className="form-group row">
                            <label className="col-form-label col-md-3">Nama Ujian</label>
                            <div className="col-md-9">
                                <input value={this.state.form.name} onChange={this.handleChange} name="name" className="form-control" disabled={this.state.loading}/>
                            </div>
                        </div>
                        <div className="form-group row">
                            <label className="col-form-label col-md-3">Keterangan</label>
                            <div className="col-md-9">
                                <textarea value={this.state.form.description} onChange={this.handleChange} name="description" className="form-control" disabled={this.state.loading} style={{resize:'none'}}/>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-9 offset-3">
                                <div className="form-group">
                                    <div className="custom-control custom-switch">
                                        <input disabled={this.state.loading} onChange={this.handleSwitch} name="question" checked={this.state.form.question} type="checkbox" className="custom-control-input" id="question"/>
                                        <label className="custom-control-label" htmlFor="question">{this.state.form.question ? 'Soal diacak' : 'Soal tidak diacak'}</label>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <div className="custom-control custom-switch">
                                        <input disabled={this.state.loading} onChange={this.handleSwitch} name="answer" checked={this.state.form.answer} type="checkbox" className="custom-control-input" id="answer"/>
                                        <label className="custom-control-label" htmlFor="answer">{this.state.form.answer ? 'Pilihan ganda diacak' : 'Pilihan ganda tidak diacak'}</label>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <div className="custom-control custom-switch">
                                        <input disabled={this.state.loading} onChange={this.handleSwitch} name="result" checked={this.state.form.result} type="checkbox" className="custom-control-input" id="result"/>
                                        <label className="custom-control-label" htmlFor="result">{this.state.form.result ? 'Hasil ditampilkan setelah peserta selesai ujian' : 'Hasil tidak ditampilkan setelah peserta selesai ujian'}</label>
                                    </div>
                                </div>
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
                </div>
            </form>
        )
    }
}
export default FormExam;
