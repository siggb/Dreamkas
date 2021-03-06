package ru.dreamkas.pos.view.fragments;

import android.app.Activity;
import android.app.Fragment;
import android.app.ProgressDialog;

import ru.dreamkas.pos.controller.RestFragmentContainer;

public class BaseFragment extends Fragment
{
    protected RestFragmentContainer changeFragmentCallback;
    public ProgressDialog progressDialog;

    @Override
    public void onAttach(Activity activity){
        super.onAttach(activity);

        try {
            changeFragmentCallback = (RestFragmentContainer) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement IChangeFragmentHandler");
        }
    }

    protected void showProgressDialog(final String msg){
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                progressDialog = new ProgressDialog(getActivity());
                progressDialog.setMessage(msg);
                progressDialog.setIndeterminate(true);
                progressDialog.setCancelable(true);
                progressDialog.show();
            }
        });
    }
}
