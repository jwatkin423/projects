<?php

namespace App\Http\Controllers;

use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller {

    protected $excelGroups = [];
    protected $dropboxPath;

    public function __construct() {
        $this->dropboxPath = '/home/adrenalads/Dropbox/AdrenEngine';
    }

    public function index() {
        Log::debug('I am in the index function');
        $excelFolders = $this->sortContents($this->dropboxPath);
        return view('excel.index')->with('dropbox_path', $this->dropboxPath)->with('excel_folders', $excelFolders['folders']);
    }


    public function viewFolder($folder = '') {
        Log::debug('I am in the viewFolder function');
        $files = [];
        $path = $this->dropboxPath . '/' . $folder;
        $results = $this->sortContents($path);
        $files = $results['files'];
        $folders = $results['folders'];

        return view('excel.contents')->with('path', $path)->with('files', $files)->with('contents', $folders);
    }


    public function viewFile(Request $request) {
        Log::debug("I am in the viewFile function");
        $tabs  = [];
        $file = $request->get('file');
        $path = $request->get('path');
        $excelFile = $path . '/' . $file;

        Log::debug('Loading excel file: ' . $file . ' ... ');
        $Sheets = Excel::load($excelFile, function($reader) use($tabs) {
        })->get();
        Log::debug('Done...');

        Log::debug('Looping through excel file: ' . $file . ' ... ');
        foreach($Sheets as $sheet) {
            if ($sheetTitle = $sheet->getTitle() !== null) {
                $tabs[] = $sheet->getTItle();
            }
        }
        Log::debug('Done...');
        print_r($tabs);
    }


    /* @TODO: Move to helper file */
    /***************************
     *
     *  Path Helper functions
     *
     *
     ******************************/


    /**
     * @param $path
     * @return array
     */
    private function sortContents($path) {
        $contents = scandir($path);
        $folders = $this->loadFolders($contents);
        $files = $this->loadExcelFiles($contents);

        return ['folders' => $folders, 'files' => $files];
    }

    /**
     * Filters out all contents except for folders that are not hidden
     *
     * @param $contents
     * @return array
     */
    private function loadFolders($contents) {
        Log::debug("I am in the LoadFolders function");
        $folders = [];

        foreach ($contents as $index => $content) {
            if (is_dir($this->dropboxPath . '/' . $content)) {
                if (!preg_match('/^[\.]{1,2}$/', $content) && !preg_match('/^[\.]{1}[a-z\-_]*/i', $content)) {
                    $folders[] = $content;
                }
            }
        }
        return $folders;
    }

    /**
     * Filters out all content except for Excel files
     *
     * @param $contents
     * @return array
     */
    private function loadExcelFiles($contents) {
        Log::debug("I am in the LoadExcelFiles function");
        $excelFiles = [];

        foreach ($contents as $content) {
            if (preg_match('/(.xlsx)$/', $content)) {
                $excelFiles[] = $content;
            }
        }

        return $excelFiles;
    }

}