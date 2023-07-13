import React, { useEffect, useState, useContext } from 'react';
import { fetchJson } from '../../../hooks/useFetchJson';
import { fetchFormData } from '../../../hooks/useFetchFormData';
import Modal from '../../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../../Context/AuthContext';
import { Notify } from '../../Modal/Notify';

export const UpdateDocAction = ({ selectedDataRow, closeModalEdit }) => {
    const { loginData } = useContext(AuthContext);
    const [selectedFileName, setSelectedFileName] = useState(selectedDataRow.file_name);
    const [selectedDataReference, setSelectedDataReference] = useState({
        value: selectedDataRow.status_id,
        label: selectedDataRow.reference_name,
    });
    const [references, setReferences] = useState([]);

    const [data, setData] = useState({
        idFile: selectedDataRow.file_id,
        _status: selectedDataRow.status_id,
        _article: '',
        _process: '',
        _action: selectedDataRow.action_id,
        fileName:selectedDataRow.file_named
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'system.file',
                referenceField: 'status_id',
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiReference/apiReference.php/listReference',
                requestData
            );

            setReferences(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        fetchDataReference();
    }, []);

    const handleUpdate = async () => {

        if (data._status === ''|| data._action === ''|| data.fileName === '') {
            handleShowError();
            return;
        }
        try {

            let inputFile = document.getElementById('inputFile').files[0];

            if (!inputFile) {
                inputFile = '';
            }

            const formdata = new FormData();
            formdata.append('idFile', data.idFile);
            formdata.append('_status', data._status);
            formdata.append('_article', data._article);
            formdata.append('_process', data._process);
            formdata.append('_action', data._action);
            formdata.append('fileName', data.fileName);
            formdata.append('file', inputFile);

            const responseData = await fetchFormData(
                'http://localhost:3000/app/apiFile/apiFile.php/updateFile',
                formdata
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalEdit();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setData({ ...data, _status: event.value });
        }
    };

    const handleChangeFileName = (event) => {
        setData({ ...data, fileName: event.target.value });
    };

    const handleFileInputChange = (event) => {
        const file = event.target.files[0];
        setSelectedFileName(file ? file.name : '');
    };

    const handleShowError = () => {
        setNotifyError(true);
        setTimeout(() => {
            setNotifyError(false);
        }, 5000);
    };

    const handleShowSuccess = () => {
        setNotifySuccess(true);
        setTimeout(() => {
            setNotifySuccess(false);
        }, 2000);
    };

    return (
        <Modal
            title={`Editar documento de la acción: ${selectedDataRow.action_name}`}
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos del documento</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form id="form">
                            <div className="row">
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Id Documento</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.file_id}
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Selecionar documento</label>
                                        <div className="input-group">
                                            <div className="custom-file">
                                                <input
                                                    type="file"
                                                    className="custom-file-input"
                                                    id="inputFile"
                                                    onChange={handleFileInputChange}
                                                />
                                                <label className="custom-file-label">
                                                    {selectedFileName}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Nombre de documento</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Nombre del archivo"
                                            defaultValue={selectedDataRow.file_named}
                                            onChange={handleChangeFileName}
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Estado</label>
                                    <Select
                                        onChange={handleChangeStatus}
                                        options={optionsReference}
                                        placeholder={'Seleccione un estado'}
                                        defaultValue={selectedDataReference}
                                        isClearable
                                    ></Select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            } // Aquí puedes pasar el contenido que desees
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalEdit}
            handleButton2Click={handleUpdate}
            closeModalEdit={closeModalEdit}
        />
    );
};
