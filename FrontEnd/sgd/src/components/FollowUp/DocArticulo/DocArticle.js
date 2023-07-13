import React, { useState, useEffect, useContext } from 'react';
import { fetchJson } from '../../../hooks/useFetchJson';
import { UpdateDocArticle } from './UpdateDocArticle';
import { InsertDocArticle } from './InsertDocArticle';
import { AuthContext } from '../../Context/AuthContext';


export const DocArticle = ({ selectedDataRow, closeTabsDocArticle, handleReload }) => {
    const { loginData } = useContext(AuthContext);
    const [dataUrl, setDataUrl] = useState([]);
    //Varibles de dataes y tabla
    const [dataTable, setDataTable] = useState([]);
    const [filteredDataTable, setFilteredDataTable] = useState([]);

    const [currentPage, setCurrentPage] = useState(1);
    const recordsPerPage = 5;
    //variable de Modales
    const [showModalUpdate, setshowModalUpdate] = useState(false);
    const [showModalInsert, setshowModalInsert] = useState(false);
    const [selectedDataRow2, setSelectedDataRow2] = useState(null);


    useEffect(() => {
        fetchDataList();
    }, []);

    const fetchDataList = async () => {
        try {
            const requestData = {
                idClient: loginData.clientId,
                idArticle: selectedDataRow.article_id
            };
            const responseData = await fetchJson(
                'http://localhost:3000/app/apiFile/apiFile.php/listFileArticle',
                requestData
            );

            if (Array.isArray(responseData.data) && responseData.data.length > 0) {
                setDataTable(responseData.data);
                setFilteredDataTable(responseData.data);
                handleReload();
            } else {
                setDataTable([]);
                setFilteredDataTable([]);
            }
        } catch (error) {
            console.error(error);
        }
    };

    const fetchDataDownload = async (idFile, nameFile) => {
        try {
            const requestData = {
                idFile: idFile
            };

            if (requestData.idFile == 'NULL') {
                return;
            }

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiFile/apiFile.php/downloadFile',
                requestData
            );

            if (responseData.desError === 'Consulta exitosa') {
                const fileUrl = responseData.data;
                const fileName = nameFile;
                initiateDownload(fileUrl, fileName);
            } else {
                setDataUrl([]);
            }
        } catch (error) {
            console.error(error);
        }
    };

    const initiateDownload = (fileUrl, fileName) => {
        fetch(fileUrl)
            .then(response => response.blob())
            .then(blob => {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', fileName);
                link.click();
            })
            .catch(error => {
                console.error(error);
            });
    };

    const handleDownloadButtonClick = (data) => {
        setSelectedDataRow2(data);
        fetchDataDownload(selectedDataRow2.file_id,selectedDataRow2.file_named);
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    const indexOfLastRecord = currentPage * recordsPerPage;
    const indexOfFirstRecord = indexOfLastRecord - recordsPerPage;
    const currentRecords = filteredDataTable.slice(indexOfFirstRecord, indexOfLastRecord);

    const handleEditButtonClick = (data) => {
        setSelectedDataRow2(data);
        setshowModalUpdate(true);
    };

    const closeModalEdit = () => {
        setshowModalUpdate(false);
        fetchDataList();
    };

    const handleInsertButtonClick = (data) => {
        setshowModalInsert(true);
    };

    const closeModalInsert = () => {
        setshowModalInsert(false);
        fetchDataList();
    };

    return (
        <div>
            <div className="row">
                <div className="col-12">
                    <div className="card card-warning">
                        <div className="card-header">
                            <h3 className="card-title">Asignar documento a articulo: <strong>{selectedDataRow.article_name}</strong></h3>
                            <div className="card-tools">
                                <button type="button"
                                    className="btn btn-tool"
                                    data-card-widget="collapse"
                                    title="Minimizar"
                                >
                                    <i className="fas fa-minus"></i>
                                </button>
                                <button type="button"
                                    className="btn btn-tool"
                                    data-card-widget="remove"
                                    title="Cerrar"
                                    onClick={closeTabsDocArticle}
                                >
                                    <i className="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div className="card-body" style={{ display: 'block' }}>


                            <div
                                id="example1_wrapper"
                                className="dataTables_wrapper dt-bootstrap4"
                            >
                                <div className="row">
                                    <div className="col-sm-12 col-md-6 ">
                                        <div className="dt-buttons btn-group flex-wrap">
                                            <button className="btn btn-secondary"
                                                tabIndex={0}
                                                aria-controls="tbActivedataTable"
                                                type="button"
                                                onClick={handleInsertButtonClick}>
                                                <span><i className="nav-icon fas fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div className="col-sm-12 col-md-6 d-flex justify-content-end">
                                        <div
                                            id="example1_filter"
                                            className="dataTables_filter"
                                        >
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-12">
                                        <table
                                            id="example1"
                                            className="table table-bordered table-striped dataTable dtr-inline"
                                            role="grid"
                                            aria-describedby="example1_info"
                                        >
                                            <thead>
                                                <tr role="row">
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        NOMBRE DE ARCHIVO
                                                    </th>
                                                    <th
                                                        className="sorting_asc"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending"
                                                    >
                                                        FECHA DE CREACION
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        ESTADO
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        ACCIONES
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Platform(s): activate to sort column ascending"
                                                        style={{ display: 'none' }}
                                                    >
                                                        ARTICLE_ID
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Platform(s): activate to sort column ascending"
                                                        style={{ display: 'none' }}
                                                    >
                                                        STATUS_ID
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Platform(s): activate to sort column ascending"
                                                        style={{ display: 'none' }}
                                                    >
                                                        FILE_ID
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {currentRecords.map((data) => (
                                                    <tr key={data.file_id}>
                                                         <td>{data.file_named}</td>
                                                        <td>{data.file_date.slice(0, 19)}</td>
                                                        <td>{data.reference_name}</td>
                                                        <td className="text-center">
                                                            <button
                                                                type="button"
                                                                className="btnEdit btn btn-sm btn-outline-primary mr-1"
                                                                title="Editar"
                                                                onClick={() => handleEditButtonClick(data)}
                                                            >
                                                                <i
                                                                    className="nav-icon far fa-edit"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                className="btnDownload btn btn-sm btn-outline-danger mr-1"
                                                                title="Descarga"
                                                                onClick={() => handleDownloadButtonClick(data)}
                                                            >
                                                                <i className="fa fa-download" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.article_id}
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.status_id}
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.file_id}
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-12 col-md-5">
                                        <div
                                            className="dataTables_info"
                                            id="example1_info"
                                            role="status"
                                            aria-live="polite"
                                        >
                                            Mostrando {indexOfFirstRecord + 1} a{' '}
                                            {Math.min(indexOfLastRecord, filteredDataTable.length)} de{' '}
                                            {filteredDataTable.length} registros
                                        </div>
                                    </div>
                                    <div className="col-sm-12 col-md-7">
                                        <div
                                            className="dataTables_paginate paging_simple_numbers"
                                            id="example1_paginate"
                                        >
                                            <ul className="pagination">
                                                <li
                                                    className={`paginate_button page-item ${currentPage === 1 ? 'disabled' : ''
                                                        }`}
                                                >
                                                    <button
                                                        className="page-link"
                                                        onClick={() => handlePageChange(currentPage - 1)}
                                                    >
                                                        Anterior
                                                    </button>
                                                </li>
                                                {Array.from(
                                                    { length: Math.ceil(filteredDataTable.length / recordsPerPage) },
                                                    (_, i) => (
                                                        <li
                                                            className={`paginate_button page-item ${currentPage === i + 1 ? 'active' : ''
                                                                }`}
                                                            key={i}
                                                        >
                                                            <button
                                                                className="page-link"
                                                                onClick={() => handlePageChange(i + 1)}
                                                            >
                                                                {i + 1}
                                                            </button>
                                                        </li>
                                                    )
                                                )}
                                                <li
                                                    className={`paginate_button page-item ${currentPage ===
                                                        Math.ceil(filteredDataTable.length / recordsPerPage)
                                                        ? 'disabled'
                                                        : ''
                                                        }`}
                                                >
                                                    <button
                                                        className="page-link"
                                                        onClick={() => handlePageChange(currentPage + 1)}
                                                    >
                                                        Siguiente
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {showModalUpdate && (
                            <UpdateDocArticle selectedDataRow={selectedDataRow2} closeModalEdit={closeModalEdit} />
                        )}
                        {showModalInsert && (
                            <InsertDocArticle selectedDataRow={selectedDataRow} closeModalInsert={closeModalInsert} />
                        )}

                    </div>
                </div>
            </div>
        </div >
    );
};
