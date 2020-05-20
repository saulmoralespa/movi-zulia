import React, { Component } from 'react';

export default class MultipleImageUploadComponent extends Component {


    constructor(props) {
        super(props)
        this.state = {
            file: []
        }
        this.uploadMultipleFiles = this.uploadMultipleFiles.bind(this)
    }

    uploadMultipleFiles(e) {
        this.setState({file: []});
        let files = e.target.files;
        let fileArray = [];
        let uploads = [];
        for (let i = 0; i < files.length; i++) {
            fileArray.push(URL.createObjectURL(files[i]));
            uploads.push(files[i]);
        }

        this.props.form.setFieldValue(this.props.field.name, fileArray);
        this.props.form.setFieldValue('uploads', uploads);

        this.setState({ file: fileArray });
    }

    render() {
        return (
            <>
                <div className="multi-preview">
                    {this.state.file.map((url, index) => (
                        <img src={url} key={index} width="100" alt="..." />
                    ))}
                </div>

                <div className="file-field input-field">
                    <div className="btn">
                        <span>Subir Imagen</span>
                        <input type="file" name={this.props.field.name}  className="form-control" onChange={this.uploadMultipleFiles} accept=".jpg, .jpeg, .png" multiple />
                    </div>
                    <div className="file-path-wrapper">
                        <input className="file-path validate" type="text" />
                    </div>
                </div>
            </>
        )
    }
}
