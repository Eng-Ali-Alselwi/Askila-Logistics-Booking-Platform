CREATE OR REPLACE Package Body IAS20241.IAS_Closing_Year_Pkg as
 
 PROCEDURE Mov_Ac_Cls_Prc ( P_Ac_cls   In  NUMBER,
                            P_Cc_cls   In  NUMBER,
                            P_Pj_cls   In  NUMBER,
                            P_Actv_cls In  NUMBER,
                            P_Cst_Cls   In  NUMBER,
                            P_Vnd_Cls   In  NUMBER,
                            P_LC_Cls    In  NUMBER,
                            P_REP_Cls    In  NUMBER,
                            P_COL_Cls    In  NUMBER,
                            P_Lng_no     In  NUMBER,
                            P_User_No    In  NUMBER,
                            P_Local_Cur  In  VARCHAR2,
                            P_Brn_no     In  NUMBER,
                            P_Brn_year   In  NUMBER,
                            P_CMP_NO     In  NUMBER,
                            P_Brn_Usr    In  NUMBER) Is

V_UsrId     VARCHAR2(30);
V_rate      NUMBER      ;
Min_DATE    DATE            :=  IAS_Gen_Pkg.Get_Frst_Day;
Max_DATE    DATE            :=  IAS_Gen_Pkg.Get_Final_Day;
P_Insrt     Number;
Rep_Bs      Number;
v_seq       Number;
Begin

   
    Begin 
        Declare
            Cursor C1 Is  
                Select 
                    account_curr.a_code 
                    , account_curr.a_cy 
                    ,Ias_Post_Dtl.Ac_Code_Dtl
                    , Ias_Post_Dtl.Ac_Code_Dtl_Sub
                    , Nvl(Ias_Post_Dtl.Ac_Dtl_Typ,0) Ac_Dtl_Typ
                    , Decode(P_cc_cls,1,Ias_Post_Dtl.cc_code,Null)  cc_code
                    , Decode(P_Pj_Cls,1,Ias_Post_Dtl.Pj_No,Null)  Pj_No
                    , Decode(P_Actv_Cls,1,Ias_Post_Dtl.Actv_No,Null)  Actv_No
                    , Ias_Post_Dtl.From_Cc_Code  From_Cc_Code
                    , Ias_Post_Dtl.F_Brn_No  F_Brn_No
                    , Ias_Post_Dtl.Lc_No
                    , Decode(P_Rep_Cls,1,Decode(Nvl(Ias_Post_Dtl.Ac_Dtl_Typ,0),3,Ias_Post_Dtl.Rep_Code,Null),Null) Rep_Code
                    , Decode(P_Col_cls,1,Ias_Post_Dtl.Col_No,Null)  Col_No
                    , Ias_Post_Dtl.Brn_No   Brn_No
                    , Ias_Post_Dtl.Cmp_No   Cmp_No
                    , nvl(Sum(amt),0)       sum_amt
                    , nvl(Sum(amt_f),0)     sum_amt_f
                    , Decode(P_Ac_cls,0,0,A_REPORT) A_REPORT
                     From account_curr,Ias_Post_Dtl,Account
               Where account.a_code      =   account_curr.a_code
                 And account_curr.a_code =   Ias_Post_Dtl.a_code
                 And account_curr.a_cy   =   Ias_Post_Dtl.a_cy
                 And ( Lc_No Is Null Or Lc_No In  ( Select Lc_No From Ias_V_Ny_Lc_Master ))
                Group By account_curr.a_code
                    , account_curr.a_cy
                    , Ias_Post_Dtl.Ac_Code_Dtl   
                    , Ias_Post_Dtl.Ac_Code_Dtl_Sub
                    , Nvl(Ias_Post_Dtl.Ac_Dtl_Typ,0)
                    , Decode(P_cc_cls,1,Ias_Post_Dtl.cc_code,Null)  
                    , Decode(P_Pj_Cls,1,Ias_Post_Dtl.Pj_No,Null)  
                    , Decode(P_Actv_Cls,1,Ias_Post_Dtl.Actv_No,Null)
                    , Ias_Post_Dtl.From_Cc_Code
                    , Ias_Post_Dtl.F_Brn_No
                    , Ias_Post_Dtl.Lc_No
                    , Decode(P_Rep_Cls,1,Decode(Nvl(Ias_Post_Dtl.Ac_Dtl_Typ,0),3,Ias_Post_Dtl.Rep_Code,Null),Null)
                    , Decode(P_Col_cls,1,Ias_Post_Dtl.Col_No,Null)
                    , Ias_Post_Dtl.Brn_No
                    , Ias_Post_Dtl.Cmp_No
                    , Decode(P_Ac_cls,0,0,A_REPORT) 
                Having nvl(Sum(amt),0) <> 0 Or nvl(Sum(amt_f),0) <>0;
                
        Begin ------------- 4 
            Begin
                Select Decode(P_Ac_cls,0,0,Report_type) Into Rep_Bs 
                  From Account_Report_type 
                 Where NVL(REPORT_BS,0)= 1;
            Exception When no_data_found Then
                Rep_Bs:=1;
            End;
            
            For i In C1 Loop
               
                If nvl(i.sum_amt_f,0) <> 0 Then
                   v_rate:=abs(i.sum_amt/i.sum_amt_f);
                ElsIf nvl(i.sum_amt_f,0) = 0 and  P_Local_Cur <> i.a_cy Then 
                   v_rate:=Ias_Gen_Pkg.Get_Cur_Rate (i.a_cy);
                End If;
               
                 If      (I.Ac_Code_Dtl   Is Not Null and I.Ac_Dtl_typ=3  And Nvl(P_Cst_Cls,0) = 0 )    -- Check C_Code Is Not Null And Cst Close --
                    Or   (I.Ac_Code_Dtl   Is Not Null and I.Ac_Dtl_typ=4  and Nvl(P_Vnd_Cls,0) = 0 )    -- Check V_Code Is Not Null And Vend Close --
                    Or   (I.Lc_No    Is Not Null And Nvl(P_Lc_Cls,0) = 0 )                             -- Check Lc_No Is Not Null And Lc Close --
                    Or   (Rep_Bs <> I.A_Report )                                                           -- Check Lc_No Is Not Null And Lc Close --
                Then
                    P_Insrt :=  0;
                Else
                    P_Insrt :=  1;
                End If;
                
                If nvl(P_Insrt,0) = 1 Then
                
                   v_UsrId:='IAS'||((P_Brn_year)+1)||P_Brn_Usr;
                   
                   Execute Immediate
                   ' Select '||v_UsrId||'.IAS_DOC_SEQ_GL.NEXTVAL From Dual ' Into v_seq;
                
                
                    Begin
                        Insert Into Ias_V_Ny_Open_Bal ( 
                                                A_CODE          ,
                                                A_CY            ,
                                                Ac_Code_Dtl     ,
                                                Ac_Code_Dtl_Sub ,
                                                Ac_Dtl_Typ      ,
                                                Rep_Code        ,                                             
                                                Col_No          ,
                                                CC_CODE         ,
                                                FROM_CC_CODE    ,
                                                Pj_No           ,
                                                Actv_No         ,
                                                F_Brn_No        ,
                                                LC_NO           ,
                                                J_AMT           ,
                                                J_AMT_F         ,
                                                AC_RATE         ,
                                                OB_PY           ,
                                                DOC_SEQUENCE    ,
                                                AD_U_ID         ,
                                                AD_DATE         ,
                                                BRN_NO          ,
                                                BRN_YEAR        ,
                                                CMP_NO          ,
                                                BRN_USR  ) 
                              Values      (
                                                i.a_code        ,
                                                i.a_cy          ,
                                                i.Ac_Code_Dtl   ,
                                                i.Ac_Code_Dtl_Sub,
                                                i.Ac_Dtl_Typ    , 
                                                i.Rep_Code      ,
                                                i.Col_No        ,
                                                i.Cc_code       ,
                                                i.FROM_CC_CODE  ,
                                                i.Pj_No         ,
                                                i.Actv_No       ,
                                                i.F_Brn_No      ,
                                                i.Lc_no         ,                                                
                                                i.sum_amt       ,
                                                Decode(P_Local_Cur,i.a_cy,0,i.sum_amt_f),
                                                Decode(P_Local_Cur,i.a_cy,1,v_rate),
                                                1               ,
                                                v_seq,
                                                P_User_No       ,
                                                Ias_Gen_Pkg.Get_CurDate,
                                                i.Brn_no        ,
                                                (P_Brn_Year+1)  ,
                                                i.CMP_NO        ,
                                                P_BRN_USR );
                    Exception When others Then
                        Raise_Application_Error(-20505,'Error In open_bal , '||SqlErrm) ;
                    End;
                End If;
            End loop;
        End;  ---------------End 4

            --##------------------------------------------------------------------------------------##--       
            
        

        Begin
            Update Ias_V_Ny_Open_Bal Set J_AMT=Null,J_AMT_F=Null 
             where Nvl(J_AMT,0)=0 
               And Nvl(J_AMT_F,0)=0;
        Exception When others Then
            Null;
        End;


    End;
End Mov_Ac_Cls_Prc;
--======================================================================================================
/*PROCEDURE Account_Closing_Prc ( P_cc_close  In NUMBER,
                                P_Brn_year  In NUMBER,
                                P_Brn_no    In NUMBER,
                                P_Lng_no    In NUMBER,
                                P_User_No   In NUMBER,
                                P_Local_Cur In VARCHAR2,
                                P_Aralt     In NUMBER,
                                P_Apalt     In NUMBER,
                                                        P_CMP_NO    In NUMBER,
                                                        P_Brn_Usr   In NUMBER) Is

v_UsrId    VARCHAR2(30);
Min_DATE   DATE;
Max_DATE   DATE;
v_lctype   NUMBER;
v_rate     NUMBER;
v_seq      NUMBER;
Begin

v_UsrId:='IAS'||(P_Brn_year+1)||P_Brn_Usr;

Min_DATE:=IAS_Gen_Pkg.Get_Frst_Day;
Max_DATE:=IAS_Gen_Pkg.Get_Final_Day;


Begin 

If Nvl(P_cc_close,0) = 1 Then --  Cost Center Mandatory
         
   Declare
     Cursor C1 Is  
                Select 
                   account_curr.a_code  --+ USE_HASH (account)  
                 , account_curr.a_cy     a_cy 
                 , Ias_Post_Dtl.Ac_Code_Dtl Ac_Code_Dtl 
                 , Ias_Post_Dtl.Ac_Dtl_Typ Ac_Dtl_Typ 
                 , Ias_Post_Dtl.cc_code  cc_code
                 , Ias_Post_Dtl.Pj_No    Pj_No
                 , Ias_Post_Dtl.Actv_No  Actv_No
                 , Ias_Post_Dtl.From_Cc_code   From_Cc_code
                 --, Ias_Post_Dtl.c_code   c_code
                 --, Ias_Post_Dtl.v_code   v_code
                 , Ias_Post_Dtl.lc_no    lc_no
                 , Ias_Post_Dtl.Rep_Code Rep_Code
                 --, Ias_Post_Dtl.cash_no  cash_no
                 , Ias_Post_Dtl.Brn_No   Brn_No
                 , Ias_Post_Dtl.Cmp_No   Cmp_No
                 , nvl(Sum(amt),0)       sum_amt
                 , nvl(Sum(amt_f),0)     sum_amt_f
            From account_curr,Ias_Post_Dtl
           Where account_curr.a_code = Ias_Post_Dtl.a_code
             and account_curr.a_cy = Ias_Post_Dtl.a_cy
             --and doc_DATE(+) Between Min_DATE and Max_DATE
             Group By account_curr.a_code
                     , account_curr.a_cy
                     , Ias_Post_Dtl.Ac_Code_Dtl 
                     , Ias_Post_Dtl.Ac_Dtl_Typ 
                     , Ias_Post_Dtl.cc_code
                     , Ias_Post_Dtl.Pj_No
                     , Ias_Post_Dtl.Actv_No
                     , Ias_Post_Dtl.From_Cc_code
                     --, Ias_Post_Dtl.c_code
                     --, Ias_Post_Dtl.v_code
                     , Ias_Post_Dtl.Lc_no
                     , Ias_Post_Dtl.Rep_Code
                     --, Ias_Post_Dtl.cash_no
                     , Ias_Post_Dtl.Brn_No
                     , Ias_Post_Dtl.Cmp_No
                     Having nvl(Sum(amt),0) <> 0 Or nvl(Sum(amt_f),0) <>0;
 
--======================================================================================= 
         Begin ------------- 4 
        
             For i In C1 Loop
               
               If nvl(i.sum_amt_f,0) <> 0 Then
                  v_rate:=abs(i.sum_amt/i.sum_amt_f);
           ElsIf nvl(i.sum_amt_f,0) = 0 and  P_Local_Cur <> i.a_cy Then 
              v_rate:=Ias_Gen_Pkg.Get_Cur_Rate (i.a_cy);
               End If;
           
           Execute Immediate ' Select '||v_UsrId||'.IAS_DOC_SEQ_GL.NEXTVAL From Dual ' Into v_seq;
                
               Begin
            Insert Into Ias_V_Ny_Open_Bal ( A_CODE,
                                            A_CY,
                                            Ac_Code_Dtl , 
                                            Ac_Dtl_Typ ,
                                            CC_CODE,
                                            Pj_No,
                                            Actv_No,
                                            FROM_CC_CODE,
                                            LC_NO,
                                            Rep_Code,
                                            J_AMT,
                                            J_AMT_F,
                                            AC_RATE,
                                            OB_PY,
                                            DOC_SEQUENCE,
                                            AD_U_ID,
                                            AD_DATE,
                                            BRN_NO,
                                            BRN_YEAR,
                                            CMP_NO   ,
                                                                                BRN_USR ) 
                                   Values ( i.a_code,
                                            i.a_cy,
                                            i.Ac_Code_Dtl,  
                                            i.Ac_Dtl_Typ,  
                                            i.cc_code,
                                            I.Pj_No,
                                            I.Actv_No,
                                            i.FROM_CC_CODE,
                                            i.Lc_no,
                                            I.Rep_Code,
                                            i.sum_amt,
                                            Decode(P_Local_Cur,i.a_cy,0,i.sum_amt_f),
                                            Decode(P_local_Cur,i.a_cy,1,v_rate),
                                            1,
                                            v_seq,
                                            P_User_No,
                                            Ias_Gen_Pkg.Get_CurDate,
                                            i.Brn_no,
                                            (P_Brn_Year+1),
                                            i.CMP_NO   ,
                                                                                P_BRN_USR );
        
                  Exception
                   When others Then
                      Raise_Application_Error(-20505,'Error In open_bal , '||SqlErrm) ;
                 End;
        
        
            End loop;
        
         End;  ---------------End 4
--##-------------------------------------------------------------##--
 Else  -- cc_type   Ias_Post_Dtl without cost_centers 
--##-------------------------------------------------------------##--
 Declare
    Cursor c1 Is  
              Select
                   account_curr.a_code  --+ USE_HASH (account)  
                 , account_curr.a_cy     acy 
                 , Ias_Post_Dtl.Ac_Code_Dtl Ac_Code_Dtl 
                 , Ias_Post_Dtl.Ac_Dtl_Typ Ac_Dtl_Typ 
                 --, Ias_Post_Dtl.c_code   c_code
                 --, Ias_Post_Dtl.v_code   v_code
                 , Ias_Post_Dtl.Lc_no    Lc_no
                 --, Ias_Post_Dtl.cash_no  cash_no
                 , Ias_Post_Dtl.Rep_Code Rep_Code 
                 , Ias_Post_Dtl.Brn_No   Brn_No
                 , Ias_Post_Dtl.Cmp_No   Cmp_No
                 , Ias_Post_Dtl.Pj_No  Pj_No
                 , Ias_Post_Dtl.Actv_No  Actv_No
                 , nvl(Sum(amt),0)       sum_amt
                 , nvl(Sum(amt_f),0)     sum_amt_f
                From account_curr ,Ias_Post_Dtl
               Where account_curr.a_code = Ias_Post_Dtl.a_code
                 and account_curr.a_cy = Ias_Post_Dtl.a_cy
                 --AND doc_DATE(+) BETWEEN Min_DATE AND Max_DATE 
                 Group by account_curr.a_code,
                          account_curr.a_cy
                          , Ias_Post_Dtl.Ac_Code_Dtl 
                          , Ias_Post_Dtl.Ac_Dtl_Typ   
                          --, Ias_Post_Dtl.c_code
                          --, Ias_Post_Dtl.v_code
                          , Ias_Post_Dtl.Lc_no
                          --, Ias_Post_Dtl.cash_no
                          , Ias_Post_Dtl.Rep_Code
                          , Ias_Post_Dtl.Brn_No
                          , Ias_Post_Dtl.Cmp_No
                          , Ias_Post_Dtl.Pj_No  
                          , Ias_Post_Dtl.Actv_No  
                     Having nvl(Sum(amt),0) <>0 Or nvl(Sum(amt_f),0) <>0;

   Begin
       For i In c1 Loop
       
               If nvl(i.sum_amt_f,0) <> 0 Then
                  v_rate:=abs(i.sum_amt/i.sum_amt_f);
               End If;
           
           Execute Immediate ' Select '||v_UsrId||'.IAS_DOC_SEQ_GL.NEXTVAL From Dual ' Into v_seq;
           
           Begin
                Insert Into Ias_V_Ny_Open_Bal ( A_CODE,
                                                A_CY,
                                                Ac_Code_Dtl , 
                                                Ac_Dtl_Typ , 
                                                LC_NO,
                                                Pj_No,
                                                Actv_No,
                                                Rep_Code ,
                                                J_AMT,
                                                J_AMT_F,
                                                AC_RATE,
                                                OB_PY, 
                                                DOC_SEQUENCE,
                                                AD_U_ID,
                                                AD_DATE,
                                                BRN_NO,
                                                BRN_YEAR,
                                                    CMP_NO   ,
                                                                                        BRN_USR ) 
                                       Values ( i.a_code,
                                                i.acy,
                                                i.Ac_Code_Dtl,  
                                                i.Ac_Dtl_Typ,  
                                                i.Lc_no,
                                                I.Pj_No ,  
                                                I.Actv_No,  
                                                i.Rep_Code,
                                                i.sum_amt,
                                                Decode(P_Local_Cur,i.acy,0,i.sum_amt_f),
                                                Decode(P_local_Cur,i.acy,1,v_rate),
                                                1,
                                                v_seq,
                                                P_User_No,
                                                Ias_Gen_Pkg.Get_CurDate,
                                                i.Brn_no,
                                                (P_Brn_Year+1),
                                                    i.CMP_NO   ,
                                                                                        P_BRN_USR );

            Exception
             when others Then
               Raise_Application_Error(-20506,'Error In open_bal , '||SqlErrm) ;
         End;
           
       End loop;
         
    End ;
 End If; -- End cc_type

--##------------------------------------------------------------------------------------##--       

    Begin
      Update Ias_V_Ny_Open_Bal Set J_AMT=Null,J_AMT_F=Null 
       where J_AMT=0 
         and J_AMT_F=0;
    Exception 
    When others Then
     Null;
    End ;

--================================================================

End;

End Account_Closing_Prc;*/
--===================================================================================

PROCEDURE Pl_Closing_Prc ( P_pl_code   In VARCHAR2,
                           P_Local_Cur In VARCHAR2,
                           P_Lang_no   In NUMBER,
                           P_user_no   In NUMBER,
                           P_Brn_year  In NUMBER,
                           P_Brn_no    In NUMBER,
                                             P_CMP_NO    In NUMBER,
                                             P_BRN_USR   In NUMBER) Is                                 
v_doc_desc  VARCHAR2(250);
v_curr_bal  NUMBER;
pl_amt      NUMBER :=0;
amount      NUMBER;
amount_f    NUMBER;
cnt           NUMBER;
v_lst_day   DATE;
v_Brn_No    NUMBER(6);

Begin
--=========================================================================================

  Insert Into Ias_pl_closing_Tmp(A_code,A_cy,Ac_Code_Dtl,Ac_Dtl_Typ,cc_code,Pj_No,Actv_No,c_code,v_code,CSH_BNK_NO,lc_no,Brn_No,Cmp_No,amt,amt_f)  
                     Select Ias_Post_Dtl.a_code ,
                            Ias_Post_Dtl.a_cy ,
                            Ias_Post_Dtl.Ac_Code_Dtl ,  
                            Ias_Post_Dtl.Ac_Dtl_Typ  ,
                            Ias_Post_Dtl.cc_code,
                            Ias_Post_Dtl.Pj_No,
                            Ias_Post_Dtl.Actv_No,
                            Ias_Post_Dtl.c_code,  
                            Ias_Post_Dtl.v_code,  
                            Ias_Post_Dtl.cash_no,  
                            Ias_Post_Dtl.lc_no,  
                            Ias_Post_Dtl.Brn_No,
                            Ias_Post_Dtl.Cmp_No,
                            nvl(sum(nvl(amt,0)),0) ,
                            nvl(sum(nvl(amt_f,0)),0) 
                       From Ias_Post_Dtl,account 
                      where Ias_Post_Dtl.a_code=account.a_code 
                        And Ias_Post_Dtl.Brn_No = P_Brn_No
                        and a_report In (select report_type From account_report_type where nvl(report_bs,0)<>1)
                        group by  Ias_Post_Dtl.a_code,
                                  Ias_Post_Dtl.a_cy,
                                  Ias_Post_Dtl.Ac_Code_Dtl , 
                                  Ias_Post_Dtl.Ac_Dtl_Typ ,
                                  Ias_Post_Dtl.cc_code,
                                  Ias_Post_Dtl.Pj_No,
                                  Ias_Post_Dtl.Actv_No,
                                  Ias_Post_Dtl.c_code,  
                                        Ias_Post_Dtl.v_code,  
                                        Ias_Post_Dtl.cash_no,  
                                        Ias_Post_Dtl.lc_no,  
                                  Ias_Post_Dtl.Brn_No,
                                  Ias_Post_Dtl.Cmp_No;

--COMMIT;
--=========================================================================================


Declare

Cursor Pl_Cv Is Select a_code,Ac_Code_Dtl,Ac_Dtl_Typ,a_cy,cc_code,Pj_No,Actv_No,c_code,v_code,lc_no,csh_bnk_no,
                       Brn_no,Cmp_no,
                       nvl(amt,0) amt,nvl(amt_f,0) amt_f
                  From Ias_pl_closing_Tmp 
                  Where nvl(amt,0)<>0
                  And Ias_pl_closing_Tmp.Brn_No = P_Brn_No
                  For Update NoWait
                  Order By Brn_no,Cmp_no ;
Begin

    Begin
      v_lst_day:=Ias_Gen_Pkg.Get_Final_Day;
     Exception 
      When Others Then
      --Ias_Gen_Pkg.Get_Msg(P_Lang_no,617)||CHR(13)||
        Raise_Application_Error(-20513,'Error In Select From detail_period , '||SqlErrm) ; 
    End;
  
 
  For D_cv In Pl_Cv Loop 
      
      If nvl(v_Brn_No,0) <> nvl(D_cv.Brn_No,0) Then
          
                    Begin 
                      IAS_Posting_Pkg.Insrt_Post_Mst( p_doc_type  => 20,
                                                      p_jv_type   => 0,
                                                      p_doc_no    => 1,
                                                      p_doc_ser   => P_Brn_Year||Lpad(D_cv.Brn_No,6,'0')||20||1,
                                                      P_J_DATE    => v_lst_day,
                                                      p_ref_no    => Null,
                                                      p_ad_u_id   => P_User_No,
                                                      p_post_u_id => P_User_No,
                                                      P_Brn_No    => D_cv.Brn_No,
                                                      P_Brn_Year  => P_Brn_Year,
                                                                                    P_CMP_NO    => D_cv.CMP_NO,
                                                                                    P_BRN_USR   => P_BRN_USR);
                     Exception 
                     When Others Then 
                      Delete From Ias_Post_Mst where doc_type=20  ;
                      Raise_Application_Error(-20514,Ias_Gen_Pkg.Get_Msg(P_Lang_no,617)||CHR(13)||SqlErrm) ;
                   End;
              
              v_Brn_No:=D_cv.Brn_No;
            
            End If;
           
       amount:=0;
       amount_f:=0;
       
          If D_cv.amt >0 Then  ---For positive value insert negative value
             amount:=-(D_cv.amt);
             If D_cv.a_cy<>P_Local_Cur Then 
                IF D_cv.amt_f >0 Then 
                   amount_f:=-(D_cv.amt_f);
                Else
                   amount_f:=abs(D_cv.amt_f);
                End If;  
             End If;
          Else                ---for Negative value insert Positive value
             amount:=abs(D_cv.amt);
             If D_cv.a_cy<>P_Local_Cur Then 
                IF D_cv.amt_f >0 Then 
                   amount_f:=-(D_cv.amt_f);
                Else
                   amount_f:=abs(D_cv.amt_f);
                End If;  
             End If;
          End If;
              

        v_curr_bal:=IAS_Posting_Pkg.Check_Acode (D_cv.a_code,D_cv.a_cy);
        v_doc_desc:=D_cv.a_cy||' '||ias_gen_pkg.get_prompt(P_Lang_no,499)||' '||D_cv.a_code;
                        
          IAS_Posting_Pkg.Insrt_Post_Dtl( p_doc_type => 20,
                                          p_jv_type  => 0,
                                        p_doc_no   => 1,
                                        p_doc_ser  => P_Brn_Year||Lpad(D_cv.Brn_No,6,'0')||20||1,
                                        p_a_code   => D_cv.a_code,
                                        p_a_cy         => D_cv.a_cy,
                                        P_AC_CODE_DTL    => D_cv.Ac_Code_Dtl ,
                                        P_AC_DTL_TYP    => D_cv.Ac_Dtl_Typ  ,
                                        p_amt           => amount,
                                        p_amt_f         => amount_f,
                                        P_J_DATE     => v_lst_day,
                                        p_j_desc     => v_doc_desc,
                                        p_cc_code     => D_cv.cc_code,
                                        P_Pj_No       => D_cv.Pj_No  ,
                                        P_Actv_No  => D_cv.Actv_No,
                                        p_c_code     => D_cv.c_code,
                                        p_v_code     => D_cv.v_code,
                                        p_cash_no     => D_cv.csh_bnk_no,
                                        p_lc_no       => D_cv.lc_no,
                                        p_cb_before=> v_curr_bal,
                                        P_Brn_No   => D_cv.Brn_No,
                                        P_Brn_Year => P_Brn_year,
                                                                        P_CMP_NO   => D_cv.CMP_NO,
                                                                        P_BRN_USR  => P_BRN_USR);  

       pl_amt:=pl_amt+D_cv.amt;

  End loop;
--===================== Pl_Acode Insert amt  
  Declare
   Cursor Plc_Cv Is 
    Select nvl(Sum(amt),0) amt,Ac_Code_Dtl,Ac_Dtl_Typ,Cc_Code,Pj_No,Actv_No,Brn_no,Cmp_no
     From Ias_pl_closing_Tmp 
      Where nvl(amt,0)<>0
      And Ias_pl_closing_Tmp.Brn_No = P_Brn_No
      Group by Ac_Code_Dtl,Ac_Dtl_Typ,Cc_Code,Pj_No,Actv_No,Brn_no,Cmp_no
      Order by Cc_Code ;
  Begin    
    
    For Dp_cv In Plc_Cv Loop
    
        v_curr_bal:=IAS_Posting_Pkg.Check_Acode (P_pl_code,P_Local_Cur);
        v_doc_desc:=ias_gen_pkg.get_prompt(P_Lang_no,499)||' '||P_pl_code;

            IAS_Posting_Pkg.Insrt_post_Dtl( p_doc_type    => 20,
                                            p_jv_type        => 0,
                                            p_doc_no        => 1,
                                            p_doc_ser        => P_Brn_Year||Lpad(Dp_cv.Brn_No,6,'0')||20||1,
                                            p_a_code        => P_pl_code,
                                            p_a_cy            => P_Local_Cur,
                                            P_Ac_Code_Dtl    => Null,
                                        P_Ac_Dtl_Typ    => Null,
                                            p_amt                => Dp_cv.amt,
                                            p_amt_f            => 0,
                                            P_J_DATE        => v_lst_day,
                                            p_j_desc        => v_doc_desc,
                                            p_cc_code      => Dp_cv.cc_code,
                                            P_PJ_No        => Dp_cv.Pj_No  ,
                                            P_Actv_No      => Dp_cv.Actv_No,
                                            p_cb_before => v_curr_bal,
                                            P_Brn_No        => Dp_cv.Brn_No,
                                            P_Brn_Year    => P_Brn_Year,
                                                                        P_CMP_NO         => Dp_cv.CMP_NO,
                                                                        P_BRN_USR        => P_BRN_USR); 
    End Loop;
    
  End;
                   
End;
Begin
    Update Ias_Post_Mst Set Doc_Post=1 Where Doc_Type=20 And Nvl(Doc_Post,0)=0;
    Update Ias_Post_Dtl Set Doc_Post=1 Where Doc_Type=20 And Nvl(Doc_Post,0)=0;
Exception when Others Then 
    Null;
End;
End Pl_Closing_Prc;  
  
--=============================================================================
PROCEDURE Move_Kimb_Data ( P_Brn_Year  In NUMBER,
                           P_Brn_No    In NUMBER,
                                                   P_Cmp_No    In NUMBER,
                                                   P_Brn_Usr   In NUMBER)Is
 v_UsrId     VARCHAR2(30);
 v_frst_day  DATE;
Begin

v_UsrId:='IAS'||(P_Brn_Year)||P_Brn_Usr; -- Prv Year


 Begin
  Execute Immediate
  'Select Min(F_DATE) From S_PRD_DTL' Into v_frst_day; 
  Exception 
    When Others Then
      Raise_Application_Error(-20515,'Error In Select Detail_Period , '||SqlErrm) ;
 End;
 
 Execute Immediate
 'Insert Into K_DETAIL ( BILL_NO, BILL_SER, K_SER, BILL_CURRENCY, A_CODE, C_CODE, K_NO, 
                                            K_DATE, K_AMT, PAID_AMT, K_NOTE,K_PY,
                                            AD_U_ID, AD_DATE, UP_U_ID, UP_DATE,
                                            BRN_NO, BRN_YEAR, CMP_NO  ,BRN_USR)
                            Select  BILL_NO, BILL_SER, K_SER||K_PY, BILL_CURRENCY, A_CODE, C_CODE, K_NO, 
                                                    K_DATE, K_AMT, PAID_AMT, K_NOTE, 1,
                                                    AD_U_ID, AD_DATE, UP_U_ID, UP_DATE,
                                                    BRN_NO, BRN_YEAR+1,CMP_NO  , BRN_USR
                                    From '||v_UsrId||'.K_DETAIL
                                 Where K_DATE >= '''||v_frst_day||'''';
 Exception 
    When Others Then
      Raise_Application_Error(-20516,'Error In Insert k_detail , '||SqlErrm) ;
End Move_Kimb_Data;

--=============================================================================
PROCEDURE Move_Bills_Data ( P_Brn_Year  In NUMBER,
                            P_Brn_No    In NUMBER,
                            P_Ser_Type  In NUMBER,
                                                    P_CMP_NO    In NUMBER,
                                                    P_Brn_Usr   In NUMBER)Is
 v_UsrId       VARCHAR2(30);
 --v_frst_day    DATE;
 --v_Inv_ser     NUMBER(2); 
 --Cons_Name_Pk  VARCHAR2(100);
 --Cons_Name_Fk  VARCHAR2(100); 
Begin

v_UsrId:='IAS'||(P_Brn_Year)||P_Brn_Usr;-- Prev. User
/*
 --v_frst_day :=To_Char(Ias_gen_Pkg.Get_Frst_Day,'dd/mm/yyyy');
 Begin
  Execute Immediate
  'Select Min(F_DATE) From S_PRD_DTL' Into v_frst_day; 
  Exception 
    When Others Then
      Raise_Application_Error(-20515,'Error In Select Detail_Period , '||SqlErrm) ;
 End;
 Begin
  Execute Immediate
  'Select Invoicing_Serials From IAS_PARA_AR' Into v_Inv_ser; 
  Exception 
    When Others Then
      v_Inv_Ser := 0;
      Raise_Application_Error(-20516,'Error In Select Inv. serial From para , '||SqlErrm) ;
 End;
 */
 
 --##  Bill Master
 Begin
     Execute Immediate
     'Insert Into 
          IAS_BILL_MST ( BILL_DOC_TYPE, BILL_NO, BILL_SER, BILL_DATE,BILL_CURRENCY,BILL_RATE,
                         STOCK_RATE,  C_CODE,  C_NAME,  A_CODE, CHEQUE_NO,NOTE_NO,CHEQUE_AMT, 
                         CHEQUE_DUE_DATE,  BILL_DUE_DATE,  BILL_POST, DISC_AMT, DISC_AMT_MST, 
                         DISC_AMT_DTL, OTHR_AMT, VAT_AMT, BILL_AMT, W_CODE, R_CODE, REP_CODE,
                         REF_NO, CASH_NO, CC_CODE, PJ_NO,CR_CARD_NO,CR_CARD_AMT,CREDIT_CARD,EXPORT, 
                         STAND_BY,COL_NO, CASH_AC_FCC, A_DESC, COMM_PER, PR_REP, External_Post, 
                         PROCESSED,C_TEL, C_ADDRESS ,DRIVER_NO, ADD_DISC_AMT_MST ,ADD_DISC_AMT_DTL,
                         EMP_NO,PAID_AMT, PAID_BILL,PAID_U_ID,PAID_DATE,SI_TYPE,
                         LOAD_NO,LOAD_SER, AD_U_ID, AD_DATE,UP_U_ID,UP_DATE,FIELD1, FIELD2, 
                         FIELD3,UNPOST_U_ID,UNPOST_DATE,POST_U_ID,POST_DATE,BILL_PY,
                                                 AUDIT_REF,AUDIT_REF_DESC,AUDIT_REF_U_ID,AUDIT_REF_DATE,BILL_NO_CONN,BILL_SER_CONN,
                                                 CR_CARD_NO_SCND,CR_CARD_AMT_SCND,CR_CARD_NO_THRD,CR_CARD_AMT_THRD,
                                                 REC_INV_BILL,REC_INV_U_ID,REC_INV_DATE,BILL_VALUED,VALUE_DATE,POS_POST,
                                                 CR_DOC_NO_REF,CR_GRP_TYPE,BILL_WITHOUT_AUTO_OTHR_AMT,
                         BRN_NO,BRN_YEAR,CMP_NO,BRN_USR )
                 Select  BILL_DOC_TYPE,BILL_NO,BILL_SER||1,BILL_DATE,BILL_CURRENCY,BILL_RATE,
                         STOCK_RATE,  C_CODE,  C_NAME,  A_CODE, CHEQUE_NO,NOTE_NO,CHEQUE_AMT, 
                         CHEQUE_DUE_DATE,  BILL_DUE_DATE,  BILL_POST, DISC_AMT, DISC_AMT_MST, 
                         DISC_AMT_DTL, OTHR_AMT, VAT_AMT, BILL_AMT, W_CODE, R_CODE, REP_CODE,
                         REF_NO, CASH_NO, CC_CODE, PJ_NO,CR_CARD_NO,CR_CARD_AMT,CREDIT_CARD,EXPORT, 
                         STAND_BY,COL_NO, CASH_AC_FCC, A_DESC, COMM_PER, PR_REP, External_Post, 
                         PROCESSED,C_TEL, C_ADDRESS ,DRIVER_NO, ADD_DISC_AMT_MST ,ADD_DISC_AMT_DTL ,
                         EMP_NO ,PAID_AMT, PAID_BILL,PAID_U_ID,PAID_DATE,SI_TYPE,
                         LOAD_NO, LOAD_SER,AD_U_ID, AD_DATE,UP_U_ID,UP_DATE,FIELD1, FIELD2, 
                         FIELD3,UNPOST_U_ID,UNPOST_DATE,POST_U_ID,POST_DATE,1,
                         AUDIT_REF,AUDIT_REF_DESC,AUDIT_REF_U_ID,AUDIT_REF_DATE,BILL_NO_CONN,BILL_SER_CONN,
                                                 CR_CARD_NO_SCND,CR_CARD_AMT_SCND,CR_CARD_NO_THRD,CR_CARD_AMT_THRD,
                                                 REC_INV_BILL,REC_INV_U_ID,REC_INV_DATE,BILL_VALUED,VALUE_DATE,POS_POST,
                                                 CR_DOC_NO_REF,CR_GRP_TYPE,BILL_WITHOUT_AUTO_OTHR_AMT,                         
                         BRN_NO,BRN_YEAR+1,CMP_NO,BRN_USR 
                    From '||v_UsrId||'.IAS_BILL_MST 
                   Where nvl(PROCESSED,0) = 0';

  Exception 
    When Others Then
      Raise_Application_Error(-20517,'Error In Insert IAS_BILL_MST , '||SqlErrm) ;
 End;
 
 --##  Bill Details
 Begin
     Execute Immediate
     'Insert Into 
          IAS_BILL_DTL ( BILL_DOC_TYPE,BILL_NO,BILL_SER,I_CODE,I_QTY,Itm_Unt, P_SIZE,P_QTY, 
                         I_PRICE, STK_COST, OUT_QTY, OUT_FREE_QTY, W_CODE, CC_CODE, SO_NO, 
                         SO_SER,EXPIRE_DATE,BATCH_NO,FREE_QTY,DIS_PER,DIS_AMT,DIS_AMT_MST, 
                         DIS_AMT_DTL,VAT_PER,VAT_AMT,OTHR_AMT, USE_SERIALNO, SERVICE_ITEM, 
                         RCRD_NO, ITEM_DESC, DOC_SEQUENCE, SI_TYPE,BARCODE, DOC_TYPE_REF ,
                         DOC_NO_REF ,DOC_SER_REF, EXTERNAL_POST ,USE_ATTCH ,REC_ATTCH ,
                         ADD_DIS_AMT_MST, ADD_DIS_AMT_DTL,BILL_PY,
                         DIS_PER2,DIS_AMT_DTL2,DIS_PER3,DIS_AMT_DTL3,DOC_DATE,RCRD_NO_REF,
                         I_LENGTH,I_WIDTH,
                         BRN_NO, BRN_YEAR,CMP_NO,BRN_USR )
                 Select  BILL_DOC_TYPE,BILL_NO,BILL_SER||1,I_CODE,I_QTY,Itm_Unt,P_SIZE,P_QTY, 
                         I_PRICE, STK_COST, OUT_QTY, OUT_FREE_QTY, W_CODE, CC_CODE, SO_NO, 
                         SO_SER,EXPIRE_DATE,BATCH_NO,FREE_QTY,DIS_PER,DIS_AMT,DIS_AMT_MST, 
                         DIS_AMT_DTL,VAT_PER,VAT_AMT,OTHR_AMT, USE_SERIALNO, SERVICE_ITEM, 
                         RCRD_NO, ITEM_DESC, DOC_SEQUENCE,SI_TYPE,BARCODE, DOC_TYPE_REF ,
                         DOC_NO_REF ,DOC_SER_REF, EXTERNAL_POST ,USE_ATTCH ,REC_ATTCH ,
                         ADD_DIS_AMT_MST, ADD_DIS_AMT_DTL,1,
                         DIS_PER2,DIS_AMT_DTL2,DIS_PER3,DIS_AMT_DTL3,DOC_DATE,RCRD_NO_REF,
                         I_LENGTH,I_WIDTH,
                         BRN_NO, BRN_YEAR+1,CMP_NO,BRN_USR 
                    From '||v_UsrId||'.IAS_BILL_DTL 
                   Where (nvl(I_QTY,0) <> nvl(OUT_QTY,0) 
                      OR nvl(FREE_QTY,0) <> nvl(OUT_FREE_QTY,0)) 
                     AND BILL_SER||1 IN(SELECT BILL_SER From IAS_BILL_MST)';
  Exception 
    When Others Then
      Raise_Application_Error(-20518,'Error In Insert IAS_BILL_DTL , '||SqlErrm) ;
 End;

--##  Other_Charges
 Begin
     Execute Immediate
     'Insert Into 
         OTHER_CHARGES ( BILL_TYPE, BILL_DOC_TYPE, BILL_NO, 
                         BILL_SER, SC_NO, A_CODE, A_CY, AC_RATE, PER, 
                         AMT, INV_ITEM, RCRD_NO,DOC_DATE, BILL_PY,EXTERNAL_POST,
                         BRN_NO, BRN_YEAR, CMP_NO , BRN_USR )
                 Select  BILL_TYPE, BILL_DOC_TYPE, BILL_NO, 
                         BILL_SER||1, SC_NO, A_CODE, A_CY, AC_RATE, PER, 
                         AMT, INV_ITEM, RCRD_NO,DOC_DATE, 1,NVL(EXTERNAL_POST,0),
                         BRN_NO, BRN_YEAR+1, CMP_NO , BRN_USR 
                    From '||v_UsrId||'.OTHER_CHARGES 
                   Where BILL_SER||1 In (SELECT BILL_SER From IAS_BILL_MST)';
  Exception 
    When Others Then
      Raise_Application_Error(-20518,'Error In Insert Other_Charges , '||SqlErrm) ;
 End;
 
 --##  Other_Charges_Items
 Begin
     Execute Immediate
     'Insert Into 
         OTHER_CHARGES_ITEMS ( BILL_TYPE, BILL_DOC_TYPE, BILL_NO, 
                               BILL_SER, SC_NO, A_CODE, A_CY, AC_RATE, PER, 
                               AMT, RCRD_NO, I_CODE,Itm_Unt, P_SIZE , W_CODE, CC_CODE,
                               DOC_DATE,UNIT_AMT,EXTERNAL_POST,
                               BILL_PY,BRN_NO, BRN_YEAR, CMP_NO , BRN_USR )
                             Select  BILL_TYPE, BILL_DOC_TYPE, BILL_NO, 
                                                           BILL_SER||1, SC_NO, A_CODE, A_CY, AC_RATE, PER, 
                                                           AMT, RCRD_NO, I_CODE,Itm_Unt, P_SIZE , W_CODE, CC_CODE,
                                                           DOC_DATE,UNIT_AMT,NVL(EXTERNAL_POST,0),
                                                           1,BRN_NO, BRN_YEAR+1, CMP_NO , BRN_USR 
                                From '||v_UsrId||'.OTHER_CHARGES_ITEMS 
                               Where BILL_SER||1 In (SELECT BILL_SER From IAS_BILL_MST)';
  Exception 
    When Others Then
      Raise_Application_Error(-20518,'Error In Insert Other_Charges_Items , '||SqlErrm) ;
 End;

 --##  Ias_Cargo_Services
 Begin
     Execute Immediate
     'Insert Into 
          IAS_CARGO_SERVICES ( BILL_SER, BILL_NO, BILL_DOC_TYPE, 
                                                           BILL_OF_LADING, GOODS_BILLNO, SERVICE_TYPE, 
                                                           WEIGHT, CUSTOMS_DESC_NO, CUSTOMS_DESC_TYPE, 
                                                           RECIEVE_COM, TRANSPORT_COM, PACKAGE_COUNT, 
                                                           SERVICE_SERIAL, A_DESC, ARRIVAL_DATE,BILL_PY, 
                                                           AD_U_ID, AD_DATE, UP_U_ID, UP_DATE, BRN_NO, BRN_YEAR,
                                                           CMP_NO , BRN_USR )
                             Select  BILL_SER||1, BILL_NO, BILL_DOC_TYPE, 
                                                           BILL_OF_LADING, GOODS_BILLNO, SERVICE_TYPE, 
                                                           WEIGHT, CUSTOMS_DESC_NO, CUSTOMS_DESC_TYPE, 
                                                           RECIEVE_COM, TRANSPORT_COM, PACKAGE_COUNT, 
                                                           SERVICE_SERIAL, A_DESC, ARRIVAL_DATE, 1,
                                                           AD_U_ID, AD_DATE, UP_U_ID, UP_DATE, BRN_NO, BRN_YEAR+1,
                                                           CMP_NO , BRN_USR 
                                From '||v_UsrId||'.IAS_CARGO_SERVICES 
                               Where BILL_SER||1 In (SELECT BILL_SER From IAS_BILL_MST)';
  Exception 
    When Others Then
      Raise_Application_Error(-20518,'Error In Insert Ias_Cargo_Services , '||SqlErrm) ;
 End;
   
End Move_Bills_Data;


--=============================================================================
PROCEDURE Move_Install_Ar_Data (  P_Brn_Year      In NUMBER,
                                  P_Brn_No        In NUMBER,
                                  P_Cmp_NO        In NUMBER,
                                  P_Brn_Usr       In NUMBER,
                                  P_Mov_Typ       In NUMBER Default 0,                                                         
                                  P_Aralt         In NUMBER,
                                  P_Local_Cur     In Varchar2,
                                  P_No_Of_Decimal In Number,
                                  P_User_No       In Number) Is
    v_UsrId     VARCHAR2(30);
    v_frst_day  DATE;
    v_i_no      NUMBER;
    cnt         NUMBER := 0 ;
    V_Yr        Number ;

    V_C_Code    Customer.C_Code%Type ;
    V_Bill_No   Number;
    V_Bill_Ser  Number ;
    V_I_Amt     Number;
    V_P_Amt     Number;
    V_J_Amt     Number;
    V_Sql       Varchar(4000);
    I_No        Number ; 
        
Begin
    V_Yr := P_Brn_Year+ 1 ;
    v_UsrId:='IAS'||(P_Brn_Year+1)||P_Brn_Usr; -- New year

    Begin
        Execute Immediate
            'Select Min(F_DATE) From '||v_UsrId||'.S_PRD_DTL' Into v_frst_day; 
    Exception 
        When Others Then
            Raise_Application_Error(-20521,'Error In Select Detail_Period , '||SqlErrm) ;
    End;
      
    If P_Mov_Typ = 0 Then -- Move By Open Bal One Record For All Installments --

            Declare
                Cursor op_cv Is 
                    SELECT  m.A_Code , 
                            m.ac_code_dtl,
                                             DECODE (NVL (m.j_amt_f, 0),
                                                     0, NVL (m.j_amt, 0),
                                                     NVL (m.j_amt_f, 0)
                                                    ) j_amt,
                                             m.cc_code, m.pj_no, m.Actv_No, m.a_cy, m.ac_rate, m.doc_sequence 
                                        FROM ias_v_ny_open_bal m, customer d
                                       WHERE m.ac_code_dtl = d.c_code
                                         AND m.ac_dtl_typ = 3
                                         AND m.ac_code_dtl IS NOT NULL
                                         AND DECODE (m.a_cy,
                                                     ias_gen_pkg.get_local_cur, NVL (m.j_amt, 0),
                                                     NVL (m.j_amt_f, 0)
                                                    ) > 0
                                    ORDER BY m.ac_code_dtl, m.a_cy, m.cc_code , D.C_Code  ;
            Begin
                For i in op_cv Loop
                    Begin
                          Select 1 
                            into cnt 
                            From ias_v_ny_installment
                           where doc_type=0 
                             and c_code = i.ac_code_dtl
                             and RowNum<=1;
                    Exception 
                        when others then
                            cnt:=0;
                    End;
                               
                    If Nvl(cnt,0) = 0 Then
                        v_i_no:=1;
                    Else
                        v_i_no:= nvl(v_i_no,0)+1;
                    End if;
                    Insert Into Ias_V_Ny_Installment( 
                                                                            DOC_TYPE,
                                                                            BILL_DOC_TYPE, 
                                                                            BILL_NO, 
                                                                            BILL_SER,
                                                                            DOC_SEQUENCE,
                                                                            I_NO, 
                                                                            I_DATE,
                                                                            DOC_DATE,
                                                                            I_AMT,
                                                                            CC_CODE,
                                                                            PJ_NO ,
                                                                            Actv_No,
                                                                            C_CODE, 
                                                                            A_CY, 
                                                                            AC_RATE,
                                                                            I_PY,
                                                                            PAID_AMT,  
                                                                            PRV_YR_AMT,                                   
                                                                            EXTERNAL_POST,
                                                                            RCRD_NO,
                                                                            BRN_NO, 
                                                                            BRN_YEAR,
                                                                            CMP_NO   ,
                                                                            BRN_USR  ,
                                                                            A_Code,
                                                                                                                                                        Dr_Typ,
                                                                                                                                                        Move_Cy  )
                                                                    VALUES( 
                                                                            0,
                                                                            0,
                                                                            0,
                                                                            0,
                                                                            i.DOC_SEQUENCE,
                                                                            Nvl(v_i_no,0),
                                                                            v_frst_day,
                                                                            v_frst_day,
                                                                            i.j_amt,
                                                                            i.cc_code,
                                                                            i.pj_no,
                                                                            i.Actv_No,
                                                                            i.ac_code_dtl,
                                                                            i.a_cy,
                                                                            i.ac_rate,
                                                                            1,
                                                                            0,
                                                                            i.j_amt,
                                                                            0,
                                                                            1,
                                                                            P_Brn_No,
                                                                            (P_Brn_Year+1),
                                                                            P_CMP_NO   ,
                                                                            P_BRN_USR,
                                                                            I.A_Code,
                                                                                                                                                        0,
                                                                                                                                                        1);   
                End Loop;
            End;
    ElsIf P_Mov_Typ =1 Then -- Move Installments Where Not Paid Manually
            Begin
            Execute Immediate
                'INSERT INTO Ias_V_Ny_Installment (
                        DOC_TYPE    , BILL_DOC_TYPE ,   BILL_NO , BILL_SER, DOC_SEQUENCE, I_NO  , I_AMT     , AC_RATE   ,  
                        I_DATE,DOC_DATE, 
                        CHEQUE_NO   ,CHEQUE_DUE_DATE,   CC_CODE , PJ_NO , Actv_No, C_CODE    , A_CY  , PRV_YR_AMT , PAID_AMT  , PAID_DATE , 
                        ADJ_AMT     , EXTERNAL_POST ,   REF_NO  , DR_NO     , I_PY  , RCRD_NO   , 
                        CMP_NO      , BRN_NO        ,   BRN_YEAR  , BRN_USR,A_Code,Dr_Typ,Move_Cy   ) 
                Select  DOC_TYPE    , BILL_DOC_TYPE ,   BILL_NO , BILL_SER  , '||v_UsrId||'.IAS_DOC_SEQ_GL.NEXTVAL, I_NO  , I_AMT     , AC_RATE   ,  
                        I_DATE,DOC_DATE, 
                        CHEQUE_NO   ,CHEQUE_DUE_DATE,   CC_CODE , PJ_NO , Actv_No, C_CODE    , A_CY  ,(Nvl(I_AMT,0)-Nvl(Paid_AMT,0)-Nvl(ADJ_AMT,0)) , PAID_AMT  , PAID_DATE , 
                        ADJ_AMT     , EXTERNAL_POST ,   REF_NO  , DR_NO     , 1     , RCRD_NO   , 
                        CMP_NO      , BRN_NO        ,'||V_Yr||'   , BRN_USR ,A_Code,Dr_Typ,Move_Cy 
                From    INSTALLMENT
                    Where I_AMT > NVL(PAID_AMT,0) + NVL(ADJ_AMT,0) 
                    And Not Exists (Select 1 From Ias_V_Ny_Installment S 
                                            Where S.BILL_NO = INSTALLMENT.BILL_NO 
                                              And S.BILL_SER = INSTALLMENT.BILL_SER 
                                              And S.DOC_SEQUENCE = INSTALLMENT.DOC_SEQUENCE)';                                                    
                    Exception 
                        When Others Then
                          --  Raise_Application_Error (-20519,'Error In Insert Installment , '||SqlErrm) ;
                              --    Raise_Application_Error (-20001,'Customer Num : '||V_C_Code||CHR(13)||' Bill Num : '||V_Bill_No||CHR(13)||' Installment Num : '||V_I_No||CHR(13)||'Bill Amnount = '||V_I_Amt||CHR(13)||'Paid Amount = '||V_P_Amt||CHR(13)||' Adjusted Amount = '||V_J_Amt|| CHR(13));        
                              Null ;
           End;



                            /* Checking If There Is An Over Payment */
                            
                            V_SQL := ' SELECT NVL(COUNT(C_CODE),0)  
                                        FROM Ias_V_ARS_UP_INSTALLMENT
                                        WHERE I_AMT < P_AMT + J_AMT ';
                            
                            Execute Immediate  V_SQL INTO CNT ;
                            
                            IF CNT > 0 THEN
                            
                            V_SQL := 'SELECT C_CODE , BILL_NO , BILL_SER , I_NO , I_AMT, P_AMT ,  J_AMT 
                                        FROM Ias_V_ARS_UP_INSTALLMENT
                                        WHERE I_AMT < P_AMT + J_AMT
                                        AND ROWNUM <=1';
                                        
                            Execute Immediate  V_SQL INTO V_C_CODE ,V_BILL_NO , V_BILL_SER , V_I_NO , V_I_AMT , V_P_AMT , V_J_AMT ;
                                                        
                            
                            END IF;
                            
                            /* End Of CHECK */
                            
                            /* Update INSTALLMENT Table */
                            
                            Execute Immediate 'UPDATE '||v_UsrId||'.INSTALLMENT M SET (M.PAID_AMT,M.ADJ_AMT) = (SELECT  NVL(D.P_AMT,0) , NVL(D.J_AMT,0)
                                                     FROM Ias_V_ARS_UP_INSTALLMENT D
                                                     WHERE M.BILL_SER = D.BILL_SER
                                                     AND M.I_NO = D.I_NO
                                                     AND M.C_CODE = D.C_CODE)';   
                                 
                    /*
                    Execute Immediate
                        'INSERT INTO Ias_V_Ny_Installment (
                                DOC_TYPE    , BILL_DOC_TYPE ,   BILL_NO , BILL_SER, DOC_SEQUENCE, I_NO  , I_AMT     , AC_RATE   ,  
                                I_DATE,DOC_DATE, 
                                CHEQUE_NO   ,CHEQUE_DUE_DATE,   CC_CODE , PJ_NO , Actv_No, C_CODE    , A_CY  , PAID_AMT , PRV_YR_AMT , PAID_DATE , 
                                ADJ_AMT     , EXTERNAL_POST ,   REF_NO  , DR_NO     , I_PY  , RCRD_NO   , 
                                CMP_NO      , BRN_NO        ,   BRN_YEAR  , BRN_USR ,A_Code,Dr_Typ,Move_Cy ) 
                        Select  DOC_TYPE    , BILL_DOC_TYPE ,   BILL_NO , BILL_SER  , '||v_UsrId||'.IAS_DOC_SEQ_GL.NEXTVAL, I_NO  , I_AMT     , AC_RATE   ,  
                                I_DATE,DOC_DATE, 
                                CHEQUE_NO   ,CHEQUE_DUE_DATE,   CC_CODE , PJ_NO , Actv_No, C_CODE    , A_CY  , PAID_AMT , (Nvl(I_AMT,0)-Nvl(Paid_AMT,0)-Nvl(ADJ_AMT,0)) PRV_YR_AMT , PAID_DATE , 
                                ADJ_AMT     , EXTERNAL_POST ,   REF_NO  , DR_NO     , 1     , RCRD_NO   , 
                                CMP_NO      , BRN_NO        ,'||V_Yr||'   , BRN_USR  ,A_Code,Dr_Typ,Move_Cy 
                        From    INSTALLMENT
                            Where (Nvl(I_AMT,0)-Nvl(Paid_AMT,0)-Nvl(ADJ_AMT,0))>=1';
                            
                         */

    ElsIf P_Mov_Typ =2 Then -- Move Installments Where Not Paid Auto
        Declare
          Cursor C_Mov_Py Is Select Distinct C_Code From Ias_Post_Dtl Order By C_Code; 
          V_Cnt_Acy  Number;
        BEGIN
              Execute Immediate 'Delete From IAS_SI_DR_DTL_TMP';      
              For I In C_Mov_Py Loop
                    Begin          
                       Select Count(Distinct A_Cy) Into V_Cnt_Acy From Ias_Post_Dtl 
                        Where C_code=I.C_code;                       
                      Exception 
                        When others Then      
                         V_Cnt_Acy := 1;
                      End;
                                      
                      If Nvl(V_Cnt_Acy,0)=1 Then
                          Ias_Dstr_Cst_Dr_Pkg.Ias_Dstr_Cst_Dr_Amt_Acy_Prc ( P_c_code        => I.C_Code,
                                                                            P_Doc_Date      => Null,
                                                                            P_Local_Cur     => P_Local_Cur,
                                                                            P_Aralt         => P_Aralt,
                                                                            P_User_No       => P_User_No,
                                                                            P_No_Of_Decimal => P_No_Of_Decimal);
                      Else
                        Ias_Dstr_Cst_Dr_Pkg.Ias_Dstr_Cst_Dr_Amt_Prc (  P_c_code        => I.C_Code,
                                                                       P_Doc_Date      => Null,
                                                                       P_Local_Cur     => P_Local_Cur,
                                                                       P_Aralt         => P_Aralt,
                                                                       P_User_No       => P_User_No,
                                                                       P_No_Of_Decimal => P_No_Of_Decimal);
                        End If;                 
            End Loop;
                              
            Begin   
                 ------------------------------------------------------------------------------------------------
                Execute Immediate 'Insert Into  IAS_V_NY_INSTALLMENT (  Doc_Type , 
                                                                        Bill_Doc_Type, 
                                                                        Bill_No, 
                                                                        Bill_Ser,
                                                                        Doc_Sequence,
                                                                        Doc_Date, 
                                                                        I_No, 
                                                                        I_Date, 
                                                                        I_Amt, 
                                                                        Ac_Rate, 
                                                                        Cc_Code, 
                                                                        Pj_No, 
                                                                        Actv_No, 
                                                                        C_Code, 
                                                                        A_Cy, 
                                                                        Paid_Amt,
                                                                        Prv_Yr_Amt,
                                                                        Paid_Date, 
                                                                        I_Py, 
                                                                        Rcrd_No,
                                                                        Cmp_No,
                                                                        Brn_No,
                                                                        Brn_Year,
                                                                        Brn_Usr,
                                                                        A_Code,
                                                                        Dr_Typ,
                                                                        Move_Cy )
                                                                 Select Doc_Type , 
                                                                        Doc_Jv_Type, 
                                                                        Doc_No, 
                                                                        Doc_Ser, 
                                                                        '||v_UsrId||'.IAS_DOC_SEQ_GL.NEXTVAL,
                                                                        Bill_Date, 
                                                                        I_No, 
                                                                        Doc_Date, 
                                                                        Nvl(I_Amt,0)-Nvl(Paid_Amt,0),
                                                                        (Nvl(I_Amt_Loc,0)/Nvl(I_Amt,0)) Ac_Rate, 
                                                                        Cc_Code, 
                                                                        Pj_No, 
                                                                        Actv_No, 
                                                                        C_Code, 
                                                                        A_Cy, 
                                                                        0, 
                                                                        Nvl(I_Amt,0)-Nvl(Paid_Amt,0) Prv_Yr_Amt,
                                                                        Null, 
                                                                        1, 
                                                                        Rcrd_No,
                                                                        Cmp_No,
                                                                        Brn_No,
                                                                        Brn_Year,
                                                                        Brn_Usr,
                                                                        A_Code,
                                                                        Dr_Typ,
                                                                        Move_Cy 
                                                                   From IAS_SI_DR_DTL_TMP
                                                                  Where Nvl(Paid_Amt_Loc,0)< Nvl(I_Amt_Loc,0)';                    
          Exception
             When No_Data_Found Then  Null;            
          End;    
       End;  
    End If;    
Exception 
    When Others Then
        Raise_Application_Error (-20519,'Error In Insert Installment , '||SqlErrm) ;
End Move_Install_Ar_Data;
--=============================================================================
PROCEDURE Move_Install_AP_Data (    P_Brn_Year  In NUMBER,
                                    P_Brn_No    In NUMBER,
                                    P_Cmp_NO    In NUMBER,
                                    P_Brn_Usr   In NUMBER,
                                    P_Mov_Typ   In NUMBER Default 0,
                                    P_Local_Cur     In Varchar2,
                                    P_No_Of_Decimal In Number,                                                           
                                    P_Apalt     In NUMBER) Is
    v_UsrId     VARCHAR2(30);
    v_frst_day  DATE;
    v_i_no      NUMBER;
    cnt         NUMBER := 0 ;
    V_Yr        Number ;
Begin
    V_Yr := P_Brn_Year+ 1 ;
    v_UsrId:='IAS'||(P_Brn_Year+1)||P_Brn_Usr; -- New year

    Begin
      Execute Immediate
      'Select Min(F_DATE) From '||v_UsrId||'.S_PRD_DTL' Into v_frst_day; 
    Exception 
        When Others Then
            Raise_Application_Error(-20521,'Error In Select Detail_Period , '||SqlErrm) ;
    End;
     
      
    If P_Mov_Typ = 0 Then -- Move By Open Bal One Record For All Installments --

            Declare
                Cursor op_cv Is 
                    Select m.ac_code_dtl,Decode(nvl(m.j_amt_f,0),0,nvl(m.j_amt,0),nvl(m.j_amt_f,0)) j_amt ,m.cc_code,m.pj_no, m.Actv_No,
                        m.a_cy,m.ac_rate
                    From ias_v_ny_open_bal m ,v_details d
                                   WHERE m.ac_code_dtl = d.v_code
                                     AND m.ac_dtl_typ = 4
                                     AND m.ac_code_dtl IS NOT NULL
                                                       And Decode(m.a_cy,IAS_GEN_PKG.GET_LOCAL_CUR,nvl(m.j_amt,0),nvl(m.j_amt_f,0)) < 0                        
                                                        order by M.ac_code_dtl,m.a_cy,m.cc_code;
        Begin
                For i in op_cv Loop
                    Begin
                        select 1 
                            into cnt 
                        From Ias_V_Ny_Installment_Pi
                            where doc_type=0 
                            and v_code = i.ac_code_dtl
                            and RowNum<=1;
                    Exception 
                        when others then
                            cnt:=0;
                    End;
                   
                    If Nvl(cnt,0) = 0 Then
                        v_i_no:=1;
                    Else
                        v_i_no:= nvl(v_i_no,0)+1;
                    End if;
                               
                    Insert Into Ias_V_Ny_Installment_Pi( 
                                                    DOC_TYPE,
                                                    BILL_DOC_TYPE, 
                                                    BILL_NO, 
                                                    BILL_SER, 
                                                    I_NO, 
                                                    I_DATE,
                                                    I_AMT,
                                                    CC_CODE,
                                                    PJ_NO,
                                                    Actv_No,
                                                    V_CODE, 
                                                    A_CY, 
                                                    AC_RATE,
                                                    I_PY,
                                                    PAID_AMT,
                                                    PRV_YR_AMT,                                  
                                                    EXTERNAL_POST,
                                                    RCRD_NO,
                                                    BRN_NO, 
                                                    BRN_YEAR,
                                                    CMP_NO   ,
                                                    BRN_USR)
                                            VALUES( 
                                                    0,
                                                    0,
                                                    0,
                                                    0,
                                                    Nvl(v_i_no,0),
                                                    v_frst_day,
                                                    Abs(i.j_amt),
                                                    i.cc_code,
                                                    i.pj_no,
                                                    i.Actv_No,
                                                    i.ac_code_dtl,
                                                    i.a_cy,
                                                    i.ac_rate,
                                                    1,
                                                    0,
                                                    Abs(i.j_amt),
                                                    0,
                                                    1,
                                                    P_Brn_No,
                                                    (P_Brn_Year+1),
                                                    P_CMP_NO   ,
                                                    P_BRN_USR);   
                End Loop;
            End;
    ElsIf P_Mov_Typ =1 Then -- Move All Installments Where Not Paid Or I_DATE In New Year --
        Begin
            Execute Immediate
                'INSERT INTO Ias_V_Ny_Installment_Pi (
                        DOC_TYPE    , BILL_DOC_TYPE ,   BILL_NO , BILL_SER  , I_NO  , I_AMT     , AC_RATE   ,  
                        I_DATE,DOC_DATE, 
                        CHEQUE_NO   ,CHEQUE_DUE_DATE,   CC_CODE , PJ_NO, Actv_No, V_CODE    , A_CY  , PAID_AMT , PRV_YR_AMT , PAID_DATE , 
                        ADJ_AMT     , EXTERNAL_POST ,   REF_NO  , DR_NO     , I_PY  , RCRD_NO   , 
                        CMP_NO      , BRN_NO        ,   BRN_YEAR   , BRN_USR ) 
                Select  DOC_TYPE    , BILL_DOC_TYPE ,   BILL_NO , BILL_SER  , I_NO  , I_AMT     , AC_RATE   ,  
                        I_DATE,DOC_DATE, 
                        CHEQUE_NO   ,CHEQUE_DUE_DATE,   CC_CODE , PJ_NO, Actv_No, V_CODE    , A_CY  , PAID_AMT  , (Nvl(I_AMT,0)-(Nvl(Paid_AMT,0)+Nvl(ADJ_AMT,0))) PRV_YR_AMT, PAID_DATE , 
                        ADJ_AMT     , EXTERNAL_POST ,   REF_NO  , DR_NO     , 1 , RCRD_NO   , 
                        CMP_NO      , BRN_NO        ,'||V_Yr||'   , BRN_USR  
                From IAS_INSTALLMENT_PI
                     Where (Nvl(I_AMT,0)-(Nvl(Paid_AMT,0)+Nvl(ADJ_AMT,0)))>=1';
            
        End;
    ElsIf P_Mov_Typ =2 Then -- Move Installments Where Not Paid Auto
        Declare
          Cursor C_Mov_Py Is Select Distinct V_Code From Ias_Post_Dtl Order By V_Code; 
          V_Cnt_Acy  Number;
        BEGIN
              Execute Immediate 'Delete From Ias_Pi_Dstr_Cr_Amt_Tmp';      
              For I In C_Mov_Py Loop
                                                
                     
                    Ias_Dstr_Vndr_Pkg.Ias_Dstr_Vndr_Cr_Inst_Amt_Prc(P_v_code     => I.V_Code,
                                                                   P_T_Date        => Ias_Gen_Pkg.Get_Final_Day,
                                                                   P_Local_Cur     => P_Local_Cur,                                                                                                                                         
                                                                   P_No_Of_Decimal => P_No_Of_Decimal
                                                                  );                                         
            End Loop;                              
            Begin   
                 ------------------------------------------------------------------------------------------------
                   Insert Into  Ias_V_Ny_Installment_Pi(Doc_Type , 
                                                        Bill_Doc_Type, 
                                                        Bill_No, 
                                                        Bill_Ser, 
                                                        Doc_Date, 
                                                        I_No, 
                                                        I_Date, 
                                                        I_Amt, 
                                                        Ac_Rate, 
                                                        Cc_Code, 
                                                        Pj_No, 
                                                        Actv_No, 
                                                        V_Code, 
                                                        A_Cy, 
                                                        Paid_Amt, 
                                                        Prv_Yr_Amt,
                                                        Paid_Date, 
                                                        I_Py, 
                                                        Rcrd_No,
                                                        Cmp_No,
                                                        Brn_No,
                                                        Brn_Year,
                                                        Brn_Usr)
                                                 Select Doc_Type , 
                                                        Doc_Jv_Type, 
                                                        Doc_No, 
                                                        Doc_Ser, 
                                                        Doc_Date, 
                                                        I_No, 
                                                        Doc_Date, 
                                                        Nvl(I_Amt,0)-Nvl(Paid_Amt,0),
                                                        (Nvl(I_Amt_Loc,0)/Nvl(I_Amt,0)) Ac_Rate, 
                                                        Cc_Code, 
                                                        Pj_No, 
                                                        Actv_No, 
                                                        V_Code, 
                                                        A_Cy, 
                                                        Paid_Amt, 
                                                        Nvl(I_Amt,0)-Nvl(Paid_Amt,0) Prv_Yr_Amt,
                                                        Paid_Date, 
                                                        1, 
                                                        Rcrd_No,
                                                        Cmp_No,
                                                        Brn_No,
                                                        Brn_Year,
                                                        Brn_Usr
                                                   From Ias_Pi_Dstr_Cr_Amt_Tmp
                                                  Where Nvl(Paid_Amt_Loc,0)< Nvl(I_Amt_Loc,0);                    
          Exception
             When No_Data_Found Then  Null;            
          End;    
       End;          
    End If;
Exception 
    When Others Then
        Raise_Application_Error(-20522,'Error In Insert Ias_Installment_Pi , '||SqlErrm) ;
End Move_Install_AP_Data;
--=============================================================================
Function Move_Cheque_Gl_Prc ( P_MOVE_CHEQ_NOT_DUE   In Number, 
                              P_VOUCHER_SERIAL      In Number, 
                              P_YBP                 In Varchar2 ) Return Varchar2 IS
  cnt           Number:=1;
  bank_ser      Number(5);
  cons_name_pk  Varchar2(100);
  cons_name_fk  Varchar2(100);
  v_frst_day    Varchar2(25); 
BEGIN

-- IN NEW YEAR

    Begin
        Select CONSTRAINT_NAME Into cons_name_pk 
          From User_Constraints
         where Table_Name='VOUCHERS' 
           and CONSTRAINT_TYPE='P' ;
                      
    Exception when others Then
          Return(SqlErrm);
    End;        
    Begin
        Select CONSTRAINT_NAME Into cons_name_fk from User_Constraints
         where Table_name='VOUCHER_DETAIL' 
           and CONSTRAINT_TYPE='R' 
           and R_CONSTRAINT_NAME=cons_name_pk;
    Exception When others Then
        Return(SqlErrm);
    End;            
    
    If Nvl(P_MOVE_CHEQ_NOT_DUE ,0) = 1 Then -- Move Cheq. Due Date Only In New Year
        Begin
          Select to_char(F_Date,'DD/MM/YYYY') 
            Into v_frst_day
            From S_Prd_Dtl 
             Where Prd_No = 1
              And RowNum <= 1 ;
        Exception when others Then
            Return(Sqlerrm);
        End;
    ElsIf Nvl(P_MOVE_CHEQ_NOT_DUE ,0) = 2 Then --Move All Cheq. Not Due From Now On ( Under Due )
        Begin
          Select to_char(F_Date-366,'DD/MM/YYYY') 
            Into v_frst_day
            From S_Prd_Dtl 
           Where Prd_No  = 1
           And   RowNum <= 1  ;
        Exception when others Then
            Return(SqlErrm);
        End;
    End If;
            
    If P_VOUCHER_SERIAL = 1 Then -- New Serial        
       
        Execute Immediate('Alter Table Voucher_detail  Disable constraint '||cons_name_fk);
        Execute Immediate('Alter Table GLS_VCHR_MST_ACCNT  Disable constraint FK_GLS_VCHR_MST_ACCNT');
        Execute Immediate('Alter Table Vouchers               Disable constraint '||cons_name_pk);
    
        --## Vouchers    
        Execute Immediate('Insert Into VOUCHERS (VOUCHER_TYPE, VOUCHER_PAY_TYPE, VOUCHER_NO,V_SER,VOUCHER_DATE, 
                                       CASH_NO,A_CY, CASH_AMT, CASH_AMTF, EX_RATE, VOUCHER_POST,REF_NO,
                                       A_DESC, STAND_BY, CHEQ_TYPE, COL_NO,REP_CODE,PR_REP, CC_CODE, PJ_NO,
                                       REC_NAME, ATTACH_NO ,VOUCHER_NO_PY,VOUCHER_DATE_PY,MOVE_CHEQ_PY,
                                     EXTERNAL_POST,V_TYPE_NO,TRANSFER,
                                       AUDIT_REF,AUDIT_REF_DESC,AUDIT_REF_U_ID,AUDIT_REF_DATE,
                                       Up_Cnt,POST_U_ID,POST_DATE,UNPOST_U_ID,UNPOST_DATE,DOC_SEQUENCE,
                                       AD_U_ID, AD_DATE ,Brn_No,Cmp_No,Brn_Usr,Brn_Year)
                                Select Distinct A.VOUCHER_TYPE, A.VOUCHER_PAY_TYPE, A.VOUCHER_NO,A.V_SER ,VOUCHER_DATE,  
                                       A.CASH_NO, A.A_CY, CASH_AMT, CASH_AMTF, A.EX_RATE, 1,A.REF_NO,
                                       A_DESC, STAND_BY, CHEQ_TYPE, A.COL_NO ,A.REP_CODE,PR_REP, A.CC_CODE, A.PJ_NO,
                                       REC_NAME, ATTACH_NO ,A.VOUCHER_NO,A.VOUCHER_DATE,1,
                                     nvl(A.EXTERNAL_POST,0),A.V_TYPE_NO,A.TRANSFER,
                                       A.AUDIT_REF,A.AUDIT_REF_DESC,A.AUDIT_REF_U_ID,A.AUDIT_REF_DATE,
                                       A.Up_Cnt,A.POST_U_ID,A.POST_DATE,A.UNPOST_U_ID,A.UNPOST_DATE,A.DOC_SEQUENCE,
                                       A.AD_U_ID, A.AD_DATE,A.Brn_No,A.Cmp_No,A.Brn_Usr,A.Brn_Year
                                FROM '||P_YBP||'.VOUCHERS A ,'||P_YBP||'.VOUCHER_DETAIL B
                                Where A.VOUCHER_PAY_TYPE = 2
                                  and A.Voucher_no=B.Voucher_no
                                  and A.Voucher_type=B.Voucher_type
                                  and A.Voucher_pay_type=B.Voucher_pay_type
                                  and A.Cash_no=B.Cash_no
                                  and A.V_SER=B.V_SER
                                  and Nvl(B.CHEQUE_VALUED,0) In (0,3)
                                  and B.DUE_DATE >='''||v_frst_day||'''
                                  and A.CHEQ_TYPE In (3,4)');
    
        --## Voucher_Detail    
        Execute Immediate('Insert Into VOUCHER_DETAIL (VOUCHER_TYPE, VOUCHER_PAY_TYPE, VOUCHER_NO, CASH_NO, V_SER,
                                       A_CY, A_CODE, AC_DESC, AC_AMT, AC_AMTF, EX_RATE,
                                       CC_CODE, PJ_NO, ACTV_NO, RCRD_NO, LC_NO, VALUE_DATE,DUE_DATE,CHEQUE_NO, CHEQUE_POST, 
                                       CHEQUE_VALUED, BANK_NO, NOTE_NO, CI_NO, K_NO,K_SER,
                                       RETURN_RES,A_CODE_END,AC_CODE_DTL_END,AC_DTL_TYP_END,EXTERNAL_POST,
                                       ENDRSMNT,ENDRSMNT_DATE,HRS_PAY_NO,BILL_NO,BILL_SER,I_NO,WO_NO,REF_NO,DOC_SEQUENCE,
                                       AD_U_ID, AD_DATE,DOC_BRN_NO,
                                       Ac_Code_Dtl, Ac_Dtl_Typ, Bank_Brn_No,
                                       Brn_No,Cmp_No,Brn_Usr,Brn_Year,COL_NO,REP_CODE )
                               Select  A.VOUCHER_TYPE, A.VOUCHER_PAY_TYPE, B.VOUCHER_NO, A.CASH_NO, B.V_SER,
                                       A.A_CY, A_CODE, AC_DESC, AC_AMT, AC_AMTF, A.EX_RATE,
                                       A.CC_CODE, A.PJ_NO, A.ACTV_NO, A.RCRD_NO, NULL, A.VALUE_DATE,A.DUE_DATE,A.CHEQUE_NO,A.CHEQUE_POST, 
                                       A.CHEQUE_VALUED, A.BANK_NO, A.NOTE_NO,A.CI_NO , A.K_NO,A.K_SER,
                                       A.RETURN_RES,A.A_CODE_END,A.AC_CODE_DTL_END,A.AC_DTL_TYP_END,NVL(A.EXTERNAL_POST,0),
                                       A.ENDRSMNT,A.ENDRSMNT_DATE,A.HRS_PAY_NO,A.BILL_NO,A.BILL_SER,A.I_NO,A.WO_NO,A.REF_NO,A.DOC_SEQUENCE,
                                       A.AD_U_ID, A.AD_DATE, A.DOC_BRN_NO,
                                       A.Ac_Code_Dtl, A.Ac_Dtl_Typ, A.Bank_Brn_No,
                                       A.Brn_No,A.Cmp_No,A.Brn_Usr,A.Brn_Year,A.COL_NO,A.REP_CODE
                               FROM '||P_YBP||'.VOUCHER_DETAIL A , VOUCHERS B 
                                     Where A.VOUCHER_PAY_TYPE=2
                                       and A.Voucher_no=B.Voucher_no  
                                       and A.Voucher_type=B.Voucher_type
                                       and A.Voucher_pay_type=B.Voucher_pay_type
                                       and A.Cash_no=B.Cash_no
                                       and A.V_SER=B.V_SER');
        
    
        --## Update Serial Vouchers
        Declare
            Cursor c1 Is Select Distinct voucher_type 
                        From Vouchers
                       where voucher_pay_type=2
            Order by voucher_type;     
        Begin            
            For i in c1 loop --(1)
                cnt:=0;
                Declare     
                v_bank_sr Number;     
                Cursor c2 Is  Select nvl(cash_at_bank.bank_sr,0) bank_sr,Vouchers.Cash_no Cash_no,Vouchers.Voucher_type,Vouchers.Voucher_no,Vouchers.Voucher_date, Brn_No, Brn_Year ,
                                     Vouchers.V_Ser , Vouchers.Rep_Code , Vouchers.Col_No , Vouchers.V_Type_No
                                From Vouchers,Cash_at_bank
                               where Vouchers.cash_no = cash_at_bank.bank_no 
                                 and Vouchers.voucher_type=i.voucher_type
                                 and Vouchers.voucher_pay_type=2
                                 --and Vouchers.Cash_no=i.cash_no
                                 Order by bank_sr,Vouchers.cash_no,Vouchers.voucher_type,Vouchers.voucher_date,Vouchers.voucher_no;
                Begin
                    For j in c2 Loop --(1)

        
                        If j.bank_sr = nvl(v_bank_sr,j.bank_sr) Then
                            cnt:=cnt+1;
                        Else
                            cnt:=1;
                        End if;
             
                        Update Voucher_detail Set voucher_no=cnt , V_Ser= Gls_Api_Trns_Pkg.Get_Doc_Srl( P_Doc_Typ      => Decode(i.voucher_type,1,2,3)
                                                                                                          , P_Brn_No       => J.Brn_No
                                                                                                          , P_Brn_Year     => J.BRN_YEAR
                                                                                                          , P_AC_CODE_DTL  => j.cash_no
                                                                                                          , P_AC_DTL_TYP   => 2
                                                                                                          , P_DOC_NO       => cnt
                                                                                                          , P_Typ_No       => J.V_Type_No
                                                                                                          , P_REP_CODE     => J.REP_CODE
                                                                                                          , P_COL_NO       => J.COL_NO
                                                                                                          ) 
                           where voucher_type=i.voucher_type 
                             and voucher_pay_type=2
                             and voucher_no = j.voucher_no
                             and V_Ser      = j.V_Ser
                             and cash_no    = j.cash_no;
        
        
                        Update Vouchers Set voucher_no=cnt , V_Ser= Gls_Api_Trns_Pkg.Get_Doc_Srl( P_Doc_Typ      => Decode(i.voucher_type,1,2,3)
                                                                                                          , P_Brn_No       => J.Brn_No
                                                                                                          , P_Brn_Year     => J.BRN_YEAR
                                                                                                          , P_AC_CODE_DTL  => j.cash_no
                                                                                                          , P_AC_DTL_TYP   => 2
                                                                                                          , P_DOC_NO       => cnt
                                                                                                          , P_Typ_No       => J.V_Type_No
                                                                                                          , P_REP_CODE     => J.REP_CODE
                                                                                                          , P_COL_NO       => J.COL_NO
                                                                                                          ) 
                           where voucher_type=i.voucher_type 
                             and voucher_pay_type=2
                             and voucher_no=j.voucher_no
                             and V_Ser      = j.V_Ser
                             and cash_no=j.cash_no;
         
                        v_bank_sr:=j.bank_sr;

                    End Loop; --(2)
                End;
            End loop; --(1)     
          
        End;    
        --## Update old yr Vouchers
        Execute Immediate('Update '||P_YBP||'.Vouchers M
                              Set Move_Cheq_Py = 1 
                            Where Voucher_Pay_Type  = 2
                              And Cheq_Type In (3,4)
                              And Exists ( Select 1 From '||P_YBP||'.Voucher_Detail D
                                         Where M.V_Ser = D.V_Ser
                                          And  Nvl(Cheque_Valued,0) In (0,3)
                                          And  D.DUE_DATE >='||''''||V_Frst_Day||''''||'
                                          And RowNum <= 1 )');   
                                              
    Elsif P_VOUCHER_SERIAL = 2 Then -- the same serial perv. year
        
        --## Vouchers    
        Execute Immediate('Insert Into VOUCHERS (VOUCHER_TYPE, VOUCHER_PAY_TYPE, VOUCHER_NO,V_SER,VOUCHER_DATE, 
                                       CASH_NO,A_CY, CASH_AMT, CASH_AMTF, EX_RATE, VOUCHER_POST,REF_NO,
                                       A_DESC, STAND_BY, CHEQ_TYPE, COL_NO,REP_CODE,PR_REP, CC_CODE, PJ_NO,
                                       REC_NAME, ATTACH_NO ,VOUCHER_NO_PY,VOUCHER_DATE_PY,MOVE_CHEQ_PY,
                                     EXTERNAL_POST,V_TYPE_NO,TRANSFER,
                                       AUDIT_REF,AUDIT_REF_DESC,AUDIT_REF_U_ID,AUDIT_REF_DATE,
                                       Up_Cnt,POST_U_ID,POST_DATE,UNPOST_U_ID,UNPOST_DATE,DOC_SEQUENCE,
                                       AD_U_ID, AD_DATE,Brn_No,Cmp_No,Brn_Usr,Brn_Year )
                                Select Distinct A.VOUCHER_TYPE, A.VOUCHER_PAY_TYPE, A.VOUCHER_NO,A.V_SER,VOUCHER_DATE, 
                                       A.CASH_NO,A.A_CY, CASH_AMT, CASH_AMTF, A.EX_RATE, 1,A.REF_NO,
                                       A_DESC, STAND_BY, CHEQ_TYPE, A.COL_NO ,A.REP_CODE,PR_REP, A.CC_CODE, A.PJ_NO,
                                       REC_NAME, ATTACH_NO ,A.VOUCHER_NO,A.VOUCHER_DATE,1,
                                     nvl(A.EXTERNAL_POST,0),A.V_TYPE_NO,A.TRANSFER,
                                       A.AUDIT_REF,A.AUDIT_REF_DESC,A.AUDIT_REF_U_ID,A.AUDIT_REF_DATE,
                                       A.Up_Cnt,A.POST_U_ID,A.POST_DATE,A.UNPOST_U_ID,A.UNPOST_DATE,A.DOC_SEQUENCE,
                                       A.AD_U_ID, A.AD_DATE, A.Brn_No,A.Cmp_No,A.Brn_Usr,A.Brn_Year
                                FROM '||P_YBP||'.VOUCHERS A ,'||P_YBP||'.VOUCHER_DETAIL B
                                Where A.VOUCHER_PAY_TYPE = 2
                                  and A.Voucher_type=B.Voucher_type
                                  and A.Voucher_pay_type=B.Voucher_pay_type
                                  and A.Voucher_no=B.Voucher_no
                                  and A.Cash_no=B.Cash_no
                                  and A.V_SER=B.V_SER
                                  and Nvl(B.CHEQUE_VALUED,0) In (0,3)
                                  and B.DUE_DATE >='''||v_frst_day||'''
                                  and A.CHEQ_TYPE In (3,4)');    
        --## Voucher_Detail    
        Execute Immediate('Insert Into VOUCHER_DETAIL (VOUCHER_TYPE, VOUCHER_PAY_TYPE, VOUCHER_NO, CASH_NO, V_SER,
                                       A_CY, A_CODE, AC_DESC, AC_AMT, AC_AMTF, EX_RATE,
                                       CC_CODE, PJ_NO, ACTV_NO, RCRD_NO, LC_NO, VALUE_DATE,DUE_DATE,CHEQUE_NO, CHEQUE_POST, 
                                       CHEQUE_VALUED, BANK_NO, NOTE_NO, CI_NO, K_NO,K_SER,
                                       RETURN_RES,A_CODE_END,AC_CODE_DTL_END,AC_DTL_TYP_END,EXTERNAL_POST,
                                       ENDRSMNT,ENDRSMNT_DATE,HRS_PAY_NO,BILL_NO,BILL_SER,I_NO,WO_NO,REF_NO,DOC_SEQUENCE,
                                       AD_U_ID, AD_DATE,DOC_BRN_NO,
                                       Ac_Code_Dtl, Ac_Dtl_Typ, Bank_Brn_No,
                                       Brn_No,Cmp_No,Brn_Usr,Brn_Year,COL_NO,REP_CODE )
                               Select  A.VOUCHER_TYPE, A.VOUCHER_PAY_TYPE, B.VOUCHER_NO, A.CASH_NO, B.V_SER,
                                       A.A_CY, A_CODE, AC_DESC, AC_AMT, AC_AMTF, A.EX_RATE,
                                       A.CC_CODE, A.PJ_NO, A.ACTV_NO, A.RCRD_NO, NULL, A.VALUE_DATE,A.DUE_DATE,A.CHEQUE_NO,A.CHEQUE_POST, 
                                       A.CHEQUE_VALUED, A.BANK_NO, A.NOTE_NO,A.CI_NO , A.K_NO,A.K_SER,
                                       A.RETURN_RES,A.A_CODE_END,A.AC_CODE_DTL_END,A.AC_DTL_TYP_END,NVL(A.EXTERNAL_POST,0),
                                       A.ENDRSMNT,A.ENDRSMNT_DATE,A.HRS_PAY_NO,A.BILL_NO,A.BILL_SER,A.I_NO,A.WO_NO,A.REF_NO,A.DOC_SEQUENCE,
                                       A.AD_U_ID, A.AD_DATE, A.DOC_BRN_NO,
                                       A.Ac_Code_Dtl, A.Ac_Dtl_Typ, A.Bank_Brn_No,
                                       A.Brn_No,A.Cmp_No,A.Brn_Usr,A.Brn_Year,A.COL_NO,A.REP_CODE
                               FROM '||P_YBP||'.VOUCHER_DETAIL A , VOUCHERS B 
                                     Where A.VOUCHER_PAY_TYPE=2
                                       and A.Voucher_type=B.Voucher_type
                                       and A.Voucher_pay_type=B.Voucher_pay_type
                                       and A.Voucher_no=B.Voucher_no
                                       and A.Cash_no=B.Cash_no
                                       and A.V_SER=B.V_SER');
        --## Update old yr Vouchers
        Execute Immediate('Update '||P_YBP||'.Vouchers M
                              Set Move_Cheq_Py = 1 
                            Where Voucher_Pay_Type  = 2
                              And Cheq_Type In (3,4)
                              And Exists ( Select 1 From '||P_YBP||'.Voucher_Detail D
                                         Where M.V_Ser = D.V_Ser
                                          And  Nvl(Cheque_Valued,0) In (0,3)
                                          And  D.DUE_DATE >='||''''||V_Frst_Day||''''||'
                                          And RowNum <= 1 )');    

    End If;
    COMMIT;    
    Execute Immediate('Alter Table vouchers ENABLE constraint '||cons_name_pk);
    Execute Immediate('Alter Table GLS_VCHR_MST_ACCNT  ENABLE constraint FK_GLS_VCHR_MST_ACCNT');
    Execute Immediate('Alter Table voucher_detail  ENABLE constraint '||cons_name_fk);
    Return(Null);      
Exception when others then
   Rollback;
   Execute Immediate('Alter Table vouchers ENABLE constraint '||cons_name_pk);
   Execute Immediate('Alter Table GLS_VCHR_MST_ACCNT  ENABLE constraint FK_GLS_VCHR_MST_ACCNT');
   Execute Immediate('Alter Table voucher_detail  ENABLE constraint '||cons_name_fk);            
   Return(SqlErrm);      
End Move_Cheque_Gl_Prc;                                                                                                             
--==============================================================================
Procedure Inv_Close_Prc( P_Inv_Type      In NUMBER ,
                         P_Frst_Date     In DATE   ,
                         P_User_No       In NUMBER ,
                         P_Brn_Year      In NUMBER ,
                         P_Brn_No        In NUMBER ,
                         P_Cmp_No        In NUMBER ,
                         P_Brn_Usr       In NUMBER ,
                         P_Vndr_Close    In NUMBER Default 0 ,
                         P_Cst_Type      In Number ,
                         P_Close_Inc_Dtl In Number Default 0 ) Is
                         
V_Ar_Undr_Dspsl_Flg             Ias_Para_Ar.Ar_Undr_Dspsl_Flg%Type;
V_Use_Auto_Rec_Whtrns_By_Wcode  Ias_Para_Inv.Use_Auto_Rec_Whtrns_By_Wcode%Type;
V_Use_Auto_Rec_Whtrns_By        Ias_Para_Inv.Use_Auto_Rec_Whtrns_By%Type;
V_Cus_Wh_Undr_Dspsl_Flg         Warehouse_Details.Cus_Wh_Undr_Dspsl_Flg%Type := 0;
V_Cons_Name_Pk                  Varchar2(250);
V_Schema_Nm Varchar2(100) := 'IAS'||To_Char(P_Brn_Year||P_Brn_Usr);
Begin
 If p_inv_type = 1 Then --AvlQty
    If  Nvl(P_Vndr_Close,0) = 1 Then
            Begin
            --## Not Use  Under Selling
                Insert Into Ias_V_Ny_Ias_Open_Stock ( I_Code       , 
                                                      I_Qty        , 
                                                      Itm_Unt       ,  
                                                      P_Size       , 
                                                      P_Qty        ,  
                                                      W_Code       , 
                                                      Whg_Code     ,
                                                      Expire_DATE  ,  
                                                      Batch_No     , 
                                                      Ad_DATE      ,
                                                      Doc_Sequence ,
                                                      Brn_No       ,
                                                      Brn_Year     ,
                                                      Cmp_No       ,
                                                      Brn_Usr      )
                 Select D.I_Code                               ,
                        Sum((Nvl(p_qty,0)+ Nvl(pf_qty,0))*in_out)  ,
                         Ias_Itm_Pkg.Get_Icode_Min_Unit(D.I_Code) Itm_Unt ,
                         1 p_size                                  ,
                         Sum((Nvl(D.p_qty,0)+ Nvl(D.pf_qty,0))*D.in_out) ,
                         D.w_code                                    ,
                         Ias_Wcode_Pkg.Get_Whg_Code  (d.W_Code)      ,
                         D.expire_date                               ,
                         D.Batch_no                                  ,
                         SysDATE                                   ,
                         0                                         ,
                         P_Brn_No,
                         p_Brn_year,
                         P_CMP_NO   ,
                         P_BRN_USR
                    From Item_Movement D , ias_v_ny_Ias_Itm_Mst M
                   Where M.i_code = D.I_code
                     And Nvl(M.Under_Selling,0) = 0
                     Group By D.I_Code,D.w_code,D.expire_date,D.batch_no
                     Having Sum((Nvl(D.p_qty,0) + Nvl(D.pf_qty,0)) * D.in_out) > 0;
            Exception
             When Others Then
              Raise_Application_Error(-20528,'Error ias_v_ny_open_stock From Item_Movement, '||SqlErrm);
            End ;  
                                     
            --## Use  Under Selling
            Begin
                Insert Into Ias_V_Ny_Ias_Open_Stock ( I_Code       , 
                                                      I_Qty        , 
                                                      Itm_Unt      ,  
                                                      P_Size       , 
                                                      P_Qty        ,  
                                                      W_Code       , 
                                                      Whg_Code     ,
                                                      Expire_DATE  ,  
                                                      Batch_No     , 
                                                      V_Code       ,
                                                      Ad_DATE      ,
                                                      Doc_Sequence ,
                                                      Brn_No       ,
                                                      Brn_Year     ,
                                                      Cmp_No       ,
                                                      Brn_Usr      )
                  Select D.I_Code                              ,
                         Sum((Nvl(D.p_qty,0)+ Nvl(D.pf_qty,0))*D.in_out)  ,
                         Ias_Itm_Pkg.Get_Icode_Min_Unit(D.I_Code) Itm_Unt ,                                    
                         1 p_size                                  ,
                         Sum((Nvl(D.p_qty,0)+ Nvl(D.pf_qty,0))*D.in_out) ,
                         D.w_code                                    ,
                         Ias_Wcode_Pkg.Get_Whg_Code  (d.W_Code)      ,
                         D.expire_date                               ,
                         D.Batch_no                                  ,
                         D.V_Code                                    ,
                         SysDATE                                   ,
                         0                                         ,
                         P_Brn_No,
                         p_Brn_year,
                         P_CMP_NO   ,
                         P_BRN_USR
                    From Item_Movement D, ias_v_ny_Ias_Itm_Mst M
                   Where M.i_code = D.I_Code
                     And Nvl(M.Under_Selling,0) = 1
                     Group By D.I_Code,D.w_code,D.expire_date,D.batch_no,D.V_Code
                     Having Sum((Nvl(D.p_qty,0) + Nvl(D.pf_qty,0)) * D.in_out) > 0 ;
            Exception
             When Others Then
              Raise_Application_Error(-20528,'Error ias_v_ny_open_stock From Item_Movement By Vndr, '||SqlErrm);
            End ;        
    Else
            If P_Cst_Type = 1 And  P_Close_Inc_Dtl = 1 Then -- Detail
                    Begin
                        Insert Into Ias_V_Ny_Ias_Open_Stock ( I_Code       , 
                                                              I_Qty        , 
                                                              Itm_Unt      ,  
                                                              P_Size       , 
                                                              P_Qty        ,  
                                                              W_Code       , 
                                                              Whg_Code     ,
                                                              Expire_DATE  ,  
                                                              Batch_No     , 
                                                              Ad_DATE      ,
                                                              Doc_Sequence ,
                                                              Stk_Cost     ,
                                                              Brn_No       ,
                                                              Brn_Year     ,
                                                              Cmp_No       ,
                                                              Brn_Usr      )
                         Select  I_Code                              ,
                                 Nvl(Cp_Qty,0)  ,
                                 Ias_Itm_Pkg.Get_Icode_Min_Unit(I_Code) Itm_Unt ,            
                                 1 p_size                                  ,
                                 Nvl(Cp_Qty,0) ,
                                 w_code                                    ,
                                 Ias_Wcode_Pkg.Get_Whg_Code  (W_Code)      ,
                                 expire_date                               ,
                                 Batch_no                                  ,
                                 SysDATE                                   ,
                                 Doc_Sequence                              ,
                                 Nvl(Stk_Cost,0)                           ,
                                 P_Brn_No,
                                 p_Brn_year,
                                 P_CMP_NO   ,
                                 P_BRN_USR
                            From Gr_Detail
                           Where Exists ( Select 1 
                                           From ias_v_ny_Ias_Itm_Mst
                                          Where ias_v_ny_Ias_Itm_Mst.i_code = Gr_Detail.I_code
                                            And RowNum <=1  ) 
                             And   Nvl(Cp_Qty,0) > 0;
                    Exception
                     When Others Then
                      Raise_Application_Error(-20528,'Error ias_v_ny_open_stock From Gr_Detail, '||SqlErrm);
                    End ;                  
            Else  -- Inc Summary
                    Begin
                        Select Nvl(Ar_Undr_Dspsl_Flg,0)
                              ,Nvl(Use_Auto_Rec_Whtrns_By_Wcode,0)
                              ,Nvl(Use_Auto_Rec_Whtrns_By,1)
                        Into
                              V_Ar_Undr_Dspsl_Flg
                             ,V_Use_Auto_Rec_Whtrns_By_Wcode
                             ,V_Use_Auto_Rec_Whtrns_By
                        From Ias_Para_Ar , Ias_Para_Inv
                        Where Rownum <=1;
                    Exception When Others Then
                        V_Ar_Undr_Dspsl_Flg := 0;
                        V_Use_Auto_Rec_Whtrns_By_Wcode := 0;
                        V_Use_Auto_Rec_Whtrns_By := 0;
                    End;
                    
                    If Nvl(V_Ar_Undr_Dspsl_Flg,0) = 1
                       And Nvl(V_Use_Auto_Rec_Whtrns_By_Wcode,0) = 1
                       And Nvl(V_Use_Auto_Rec_Whtrns_By,1) In (1,3)
                       Then
                            Begin
                                Select 1
                                  Into V_Cus_Wh_Undr_Dspsl_Flg
                                From Item_Movement , Warehouse_Details
                                Where Item_Movement.W_Code = Warehouse_Details.W_Code
                                    And Item_Movement.C_Code Is Not Null
                                    And Nvl(Cus_Wh_Undr_Dspsl_Flg,0) =1
                                    And Rownum <=1;
                            Exception When Others Then
                                V_Cus_Wh_Undr_Dspsl_Flg := 0;
                            End;
                    End If;
                    
                    If Nvl(V_Cus_Wh_Undr_Dspsl_Flg,0) = 1 Then
                        Begin
                            Select Constraint_Name Into V_Cons_Name_Pk 
                              From User_Constraints
                             Where Table_Name      = 'IAS_OPEN_STOCK' 
                               And Constraint_Type = 'P' ;               
                        Exception When Others Then
                              V_Cons_Name_Pk := Null;
                        End;
                        
                        If V_Cons_Name_Pk Is Not Null Then
                            Begin
                                Execute Immediate 'Alter Table '||V_Schema_Nm||'.'||'IAS_OPEN_STOCK  Disable Constraint '||V_Cons_Name_Pk;
                            Exception When Others Then
                                Raise_Application_Error(-20526,'Unable To Disable Constraint '||V_Cons_Name_Pk||Chr(13)||SqlErrm);
                            End;
                            
                            Begin
                                Execute Immediate 'ALTER TABLE '||V_Schema_Nm||'.'||'IAS_OPEN_STOCK ADD CONSTRAINT IASOPSTK_UQ UNIQUE (I_CODE, ITM_UNT, W_CODE, EXPIRE_DATE, BATCH_NO, C_CODE)';
                            Exception When Others Then
                                Raise_Application_Error(-20527,'Unable To ADD Constraint IASOPSTK_UQ UNIQUE (I_CODE, ITM_UNT, W_CODE, EXPIRE_DATE, BATCH_NO, C_CODE)'||Chr(13)||SqlErrm);
                            End;
                            
                            Begin
                                Insert Into Ias_V_Ny_Ias_Open_Stock ( I_Code       , 
                                                                      I_Qty        , 
                                                                      Itm_Unt      ,  
                                                                      P_Size       , 
                                                                      P_Qty        ,  
                                                                      W_Code       , 
                                                                      Whg_Code     ,
                                                                      Expire_DATE  ,  
                                                                      Batch_No     , 
                                                                      Ad_DATE      ,
                                                                      Doc_Sequence ,
                                                                      Brn_No       ,
                                                                      Brn_Year     ,
                                                                      Cmp_No       ,
                                                                      Brn_Usr      ,
                                                                      C_Code
                                                                      )
                                 Select  I_Code                              ,
                                         Sum((Nvl(p_qty,0)+ Nvl(pf_qty,0))*in_out)  ,
                                         Ias_Itm_Pkg.Get_Icode_Min_Unit(I_Code) Itm_Unt ,            
                                         1 p_size                                  ,
                                         Sum((Nvl(p_qty,0)+ Nvl(pf_qty,0))*in_out) ,
                                         w_code                                    ,
                                         Ias_Wcode_Pkg.Get_Whg_Code  (W_Code)      ,
                                         expire_date                               ,
                                         Batch_no                                  ,
                                         SysDATE                                   ,
                                         0                                         ,
                                         P_Brn_No,
                                         p_Brn_year,
                                         P_CMP_NO   ,
                                         P_BRN_USR,
                                         C_Code                                            
                                        From (                                            
                                        Select  I_Code                              ,
                                         p_qty ,
                                         pf_qty ,            
                                         p_size                                  ,
                                         in_out ,
                                         w_code                                    ,
                                         expire_date                               ,
                                         Batch_no                                  ,
                                          ( Case When 1 = (Select Nvl(CUS_WH_UNDR_DSPSL_FLG,0) From Warehouse_Details Where W_Code = Item_Movement.W_Code And Rownum <=1)
                                                Then C_Code
                                                Else
                                                   Null
                                           End
                                         ) C_Code
                                    From Item_Movement
                                   Where Exists ( Select 1 
                                                   From ias_v_ny_Ias_Itm_Mst
                                                  Where ias_v_ny_Ias_Itm_Mst.i_code = item_movement.I_code
                                                    And RowNum <=1  
                                                 )
                                     )
                                     Group By I_Code,w_code,expire_date,batch_no,C_Code
                                     Having Sum((Nvl(p_qty,0) + Nvl(pf_qty,0)) * in_out) > 0;
                            Exception
                             When Others Then
                              Raise_Application_Error(-20528,'Error ias_v_ny_open_stock From Item_Movement, '||SqlErrm);
                            End ;  
                        
                            
                        End If; 
                    Else
                        Begin
                            Insert Into Ias_V_Ny_Ias_Open_Stock ( I_Code       , 
                                                                  I_Qty        , 
                                                                  Itm_Unt      ,  
                                                                  P_Size       , 
                                                                  P_Qty        ,  
                                                                  W_Code       , 
                                                                  Whg_Code     ,
                                                                  Expire_DATE  ,  
                                                                  Batch_No     , 
                                                                  Ad_DATE      ,
                                                                  Doc_Sequence ,
                                                                  Brn_No       ,
                                                                  Brn_Year     ,
                                                                  Cmp_No       ,
                                                                  Brn_Usr      )
                             Select  I_Code                              ,
                                     Sum((Nvl(p_qty,0)+ Nvl(pf_qty,0))*in_out)  ,
                                     Ias_Itm_Pkg.Get_Icode_Min_Unit(I_Code) Itm_Unt ,            
                                     1 p_size                                  ,
                                     Sum((Nvl(p_qty,0)+ Nvl(pf_qty,0))*in_out) ,
                                     w_code                                    ,
                                     Ias_Wcode_Pkg.Get_Whg_Code  (W_Code)      ,
                                     expire_date                               ,
                                     Batch_no                                  ,
                                     SysDATE                                   ,
                                     0                                         ,
                                     P_Brn_No,
                                     p_Brn_year,
                                     P_CMP_NO   ,
                                     P_BRN_USR
                                From Item_Movement
                               Where Exists ( Select 1 
                                               From ias_v_ny_Ias_Itm_Mst
                                              Where ias_v_ny_Ias_Itm_Mst.i_code = item_movement.I_code
                                                And RowNum <=1  ) 
                                 Group By I_Code,w_code,expire_date,batch_no
                                 Having Sum((Nvl(p_qty,0) + Nvl(pf_qty,0)) * in_out) > 0;
                        Exception
                         When Others Then
                          Raise_Application_Error(-20528,'Error ias_v_ny_open_stock From Item_Movement, '||SqlErrm);
                        End ;  
                    End If;    
        End If ;
    End If ;
 
    --# Use Serial No
    Begin
       Insert Into Ias_V_Ny_ias_item_serialno(Doc_Type,
                                              Bill_Doc_Type,
                                              Doc_No,
                                              Doc_Ser,
                                              Doc_Date,
                                              I_Code,
                                              Itm_Unt,
                                              P_Size,
                                              W_Code, 
                                              Serialno,
                                              Bill_Cost,
                                              Rcrd_No_Doc,
                                              In_Out,
                                              Cc_Code,
                                              Expire_DATE,
                                              Batch_No,
                                              Free_Flg, 
                                              Rcrd_No,
                                              External_Post,
                                              Out_No,
                                              Out_Gr_Ser,
                                              Ad_DATE,
                                              Up_DATE,
                                              Brn_No, 
                                              Brn_Year,
                                              Cmp_No   ,
                                              Brn_Usr) 
                                            Select 0,
                                                   Null,
                                                   0,
                                                   0,
                                                   P_Frst_Date,
                                                   I_Code,
                                                   Ias_Itm_Pkg.Get_Icode_Min_Unit (I_Code) Itm_Unt,
                                                   1 P_Size ,
                                                   W_Code, 
                                                   Serialno,
                                                   1,
                                                   1,
                                                   1,
                                                   Null,
                                                   Expire_DATE,
                                                   Batch_No,
                                                   0, 
                                                   1,
                                                   1,
                                                   Null,
                                                   Null,
                                                   SysDate,
                                                   Null,
                                                   P_Brn_No,
                                                   P_Brn_Year,
                                                   P_Cmp_No,
                                                   P_Brn_Usr 
                                               From ias_item_serialno
                                                 Where Exists ( Select 1 
                                                                 From ias_v_ny_Ias_Itm_Mst
                                                                  Where ias_v_ny_Ias_Itm_Mst.i_code = ias_item_serialno.i_code
                                                                   And RowNum <=1  )                        
                                                  Group By I_Code,W_Code,Expire_Date,Batch_No,Serialno
                                                   Having Nvl(Sum(In_Out),0) > 0  ;
    Exception
        When Others Then
         Raise_Application_Error(-20530,'Error Ias_V_Ny_Ias_Item_Serialno From Ias_Item_Serialno '||SqlErrm);         
    End; 
    -- # Use Attach Item 
    Begin  
                Insert Into Ias_V_Ny_Ias_Itm_Attach ( I_Code, 
                                                    Flex_No, 
                                                    Flex_Field, 
                                                    Attch_No1, 
                                                    Attch_No2, 
                                                    Attch_No3, 
                                                    Attch_No4, 
                                                    Attch_No5,
                                                    Attch_Desc_No1,
                                                    Attch_Desc_No2,
                                                    Attch_Desc_No3,
                                                    Attch_Desc_No4,
                                                    Attch_Desc_No5,
                                                    Ad_U_Id, 
                                                    Ad_Date,
                                                    Up_U_Id, 
                                                    Up_Date)
                 Select   I_Code, 
                        Flex_No, 
                        Flex_Field, 
                        Attch_No1, 
                        Attch_No2, 
                        Attch_No3, 
                        Attch_No4, 
                        Attch_No5,
                        Attch_Desc_No1,
                        Attch_Desc_No2,
                        Attch_Desc_No3,
                        Attch_Desc_No4,
                        Attch_Desc_No5,
                        Ad_U_Id, 
                        Ad_Date,
                        Up_U_Id, 
                        Up_Date
                From Ias_Itm_Attach ;    
    Exception
        When Others Then
        Raise_Application_Error(-20531,'Error In Ias_V_Ny_Ias_Itm_Attach From Ias_Itm_Attach '||SqlErrm);
    End ;      
    Begin  
      Insert Into Ias_V_Ny_Itm_Attach_Movement(I_Code,
                                               Itm_Unt,
                                               P_Size,
                                               Attch_No1,
                                               Attch_Desc_No1,
                                               Attch_No2, 
                                               Attch_Desc_No2,
                                               Attch_No3,
                                               Attch_Desc_No3,
                                               Attch_No4,
                                               Attch_Desc_No4,
                                               Attch_No5,
                                               Attch_Desc_No5,
                                               Flex_No,
                                               Attch_Note,
                                               Doc_Type,
                                               Bill_Doc_Type, 
                                               Doc_No,
                                               Doc_Ser,
                                               W_Code,
                                               Bill_Cost,
                                               Rec_Attch,
                                               In_Out,Cc_Code,
                                               Expire_DATE, 
                                               Batch_No,
                                               I_Qty,
                                               P_Qty,
                                               Free_Qty,
                                               Pf_Qty,
                                               Rcrd_No,
                                               External_Post,
                                               Ad_U_Id, 
                                               Ad_DATE,
                                               Up_U_Id,
                                               Up_DATE,
                                               Flex_Field,
                                               Rcrd_No_Doc, 
                                               C_Code,
                                               Doc_Type_Ref,
                                               Doc_No_Ref,
                                               Doc_Ser_Ref,
                                               Out_No,
                                               Out_Gr_Ser,
                                               Doc_Date ,
                                               A_Cy,
                                               Stk_Rate,
                                               Brn_No,
                                               Cmp_No,
                                               Brn_Year,                                               
                                               Brn_Usr)
                                                                       Select I_Code,
                                                                              Ias_Itm_Pkg.Get_Icode_Min_Unit(I_Code) Itm_Unt,
                                                                              1,
                                                                              Attch_No1,
                                                                              Attch_Desc_No1,
                                                                              Attch_No2, 
                                                                              Attch_Desc_No2,
                                                                              Attch_No3,
                                                                              Attch_Desc_No3,
                                                                              Attch_No4,
                                                                              Attch_Desc_No4,
                                                                              Attch_No5,
                                                                              Attch_Desc_No5,
                                                                              Flex_No,
                                                                              Null,
                                                                              0,
                                                                              Null, 
                                                                              0,
                                                                              0,
                                                                              W_Code,
                                                                              1,
                                                                              Null,
                                                                              1,
                                                                              Null,
                                                                              Expire_DATE, 
                                                                              Batch_No,
                                                                              Nvl(Sum(In_Out * (Nvl(P_Qty,0)+ Nvl(Pf_Qty,0))),0) I_Qty,
                                                                              Nvl(Sum(In_Out * (Nvl(P_Qty,0)+ Nvl(Pf_Qty,0))),0) P_Qty,
                                                                              0 Free_Qty,
                                                                              0 Pf_Qty,
                                                                              1,
                                                                              0,
                                                                              Null, 
                                                                              SysDATE,
                                                                              Null,
                                                                              Null,
                                                                              Flex_Field,
                                                                              1, 
                                                                              Null,
                                                                              Null,
                                                                              Null,
                                                                              Null,
                                                                              Null,
                                                                              Null,
                                                                              P_Frst_Date ,
                                                                              Ias_Gen_Pkg.Get_Stk_Cur ,
                                              Ias_Gen_Pkg.Get_Cur_Rate (Ias_Gen_Pkg.Get_Stk_Cur ),
                                                                              P_Brn_No,
                                                                              P_Cmp_No,
                                                                              P_Brn_Year,
                                                                              P_Brn_Usr
                                                                        From ias_itm_attach_movement
                                                                          Group By  I_Code,
                                                                                    Attch_No1,
                                                                                    Attch_Desc_No1,
                                                                                    Attch_No2, 
                                                                                    Attch_Desc_No2,
                                                                                    Attch_No3,
                                                                                    Attch_Desc_No3,
                                                                                    Attch_No4,
                                                                                    Attch_Desc_No4,
                                                                                    Attch_No5,
                                                                                    Attch_Desc_No5,
                                                                                    Flex_No,
                                                                                    W_Code,
                                                                                    Expire_DATE,
                                                                                    Batch_No,
                                                                                    Flex_Field
                                                                            Having  Nvl(Sum(In_Out * (Nvl(P_Qty,0)+ Nvl(Pf_Qty,0))),0)  > 0 ;              
    Exception
        When Others Then
        Raise_Application_Error(-20532,'Error In Ias_V_Ny_Itm_Attach_Movement From Ias_Itm_Attach_Movement '||SqlErrm);
    End ;     
 Else -- Man. Inv. Qty
    Begin
        Insert Into Ias_V_Ny_Ias_Open_Stock ( I_Code        ,  
                                              I_Qty         , 
                                              Itm_Unt       , 
                                              P_Size        , 
                                              P_Qty         , 
                                              W_Code        , 
                                              Whg_Code      ,
                                              Expire_DATE   , 
                                              Batch_No      , 
                                              Ad_DATE       ,
                                              Doc_Sequence  ,
                                              Brn_Year,
                                              Brn_no,
                                              Cmp_No   ,
                                              Brn_Usr)
                                                                     Select I_Code                  ,
                                                                            Sum(Nvl(P_Qty,0)) P_Qty ,
                                                                            Ias_Itm_Pkg.Get_Icode_Min_Unit (I_Code)  ,
                                                                            1                       ,
                                                                            Sum(Nvl(P_Qty,0)) P_Qty ,
                                                                            W_Code                  ,
                                                                            Ias_Wcode_Pkg.Get_Whg_Code  (W_Code)  ,
                                                                            Expire_Date             ,
                                                                            Batch_No                ,
                                                                            SysDATE                 ,
                                                                            0                       ,
                                                                            P_Brn_Year  ,
                                                                            P_Brn_no,
                                                                            P_Cmp_No,
                                                                            P_Brn_Usr  
                                                                      From Detail_Inv 
                                                                       Where  Exists (Select 1 From Ias_V_Ny_Ias_Itm_Mst
                                                                                       Where Ias_V_Ny_Ias_Itm_Mst.I_Code = Detail_Inv.I_Code
                                                                                        And  Rownum <=1  ) 
                                                                                        And P_Qty >= 0
                                                                                        And P_Qty Is Not Null
                                                                          And Exists ( Select 1 From Master_Inv 
                                                                                        Where Master_Inv.Inv_No = Detail_Inv.Inv_No
                                                                                         And Nvl(Annual,0)      = 1
                                                                                         And Rownum            <= 1 )
                                                                       Group By I_Code,W_Code,Expire_Date,Batch_No;
    Exception
        When Others Then
          Raise_Application_Error(-20529,'Error When Insert Into Ias_V_Ny_Open_Stock From Detail_Inv '||Sqlerrm);
    End ;  
    -- # Use Attach Item 
    Begin  
                Insert Into Ias_V_Ny_Ias_Itm_Attach ( I_Code, 
                                                    Flex_No, 
                                                    Flex_Field, 
                                                    Attch_No1, 
                                                    Attch_No2, 
                                                    Attch_No3, 
                                                    Attch_No4, 
                                                    Attch_No5,
                                                    Attch_Desc_No1,
                                                    Attch_Desc_No2,
                                                    Attch_Desc_No3,
                                                    Attch_Desc_No4,
                                                    Attch_Desc_No5,
                                                    Ad_U_Id, 
                                                    Ad_Date,
                                                    Up_U_Id, 
                                                    Up_Date)
                 Select   I_Code, 
                        Flex_No, 
                        Flex_Field, 
                        Attch_No1, 
                        Attch_No2, 
                        Attch_No3, 
                        Attch_No4, 
                        Attch_No5,
                        Attch_Desc_No1,
                        Attch_Desc_No2,
                        Attch_Desc_No3,
                        Attch_Desc_No4,
                        Attch_Desc_No5,
                        Ad_U_Id, 
                        Ad_Date,
                        Up_U_Id, 
                        Up_Date
                From Ias_Itm_Attach ;    
    Exception
        When Others Then
        Raise_Application_Error(-20525,'Error In Ias_V_Ny_Ias_Itm_Attach From Ias_Itm_Attach '||SqlErrm);
    End ;
    Begin  
       Insert Into Ias_V_Ny_Itm_Attach_Movement(I_Code,
                                                Itm_Unt,
                                                P_Size,
                                                Attch_No1,
                                                Attch_Desc_No1,
                                                Attch_No2, 
                                                Attch_Desc_No2,
                                                Attch_No3,
                                                Attch_Desc_No3,
                                                Attch_No4,
                                                Attch_Desc_No4,
                                                Attch_No5,
                                                Attch_Desc_No5,
                                                Flex_No,
                                                Attch_Note,
                                                Doc_Type,
                                                Bill_Doc_Type, 
                                                Doc_No,
                                                Doc_Ser,
                                                W_Code,
                                                Bill_Cost,
                                                Rec_Attch,
                                                In_Out,
                                                Cc_Code,
                                                Expire_DATE, 
                                                Batch_No,
                                                I_Qty,
                                                P_Qty,
                                                Free_Qty,
                                                Pf_Qty,
                                                Rcrd_No,
                                                External_Post,
                                                Ad_U_Id, 
                                                Ad_DATE,
                                                Up_U_Id,
                                                Up_DATE,
                                                Flex_Field,
                                                Rcrd_No_Doc, 
                                                C_Code,
                                                Doc_Type_Ref,
                                                Doc_No_Ref,
                                                Doc_Ser_Ref,
                                                Out_No,
                                                Out_Gr_Ser,
                                                Doc_Date ,
                                                A_Cy,
                                                Stk_Rate,
                                                Brn_No,
                                                Brn_Year,
                                                Cmp_No   ,
                                                Brn_Usr)
        Select I_code,
               Ias_Itm_Pkg.Get_Icode_Min_Unit (I_Code) Itm_Unt,
               1,
               attch_no1,
               attch_desc_no1,
               attch_no2, 
               attch_desc_no2,
               attch_no3,
               attch_desc_no3,
               attch_no4,
               attch_desc_no4,
               attch_no5,
               attch_desc_no5,
               flex_no,
               Null,
               0,
               Null, 
               0,
               0,
               w_code,
               1,
               Null,
               1,
               Null,
               expire_DATE, 
               batch_no,
               Sum(i_qty) i_qty,
               Sum(p_qty) p_qty,
               0 free_qty,
               0 pf_qty,
               1,
               1,
               Null, 
               SysDATE,
               Null,
               Null,
               Null,
               1, 
               Null,
               Null,
               Null,
               Null,
               Null,
               Null ,
               P_Frst_Date ,
                             Ias_Gen_Pkg.Get_Stk_Cur ,
                             Ias_Gen_Pkg.Get_Cur_Rate (Ias_Gen_Pkg.Get_Stk_Cur ),
               P_Brn_no,
               P_Brn_Year,
               P_Cmp_No,
               P_Brn_Usr
         From detail_inv 
          Where Nvl(Use_Attch,0) = 1 
           And Exists (Select 1 
                        From  ias_v_ny_Ias_Itm_Mst
                         Where ias_v_ny_Ias_Itm_Mst.i_code = detail_inv.I_Code
                          And RowNum <=1  ) 
                          And p_qty >= 0
           And p_qty Is Not Null
           And Exists ( Select 1 From master_inv 
                         Where master_inv.inv_no = detail_inv.inv_no
                          And Nvl(annual,0)      = 1
                          And RowNum            <= 1 )
         Group By I_code,
                  w_code,
                  expire_DATE,
                  batch_no,
                  attch_no1,
                  attch_desc_no1,
                  attch_no2,
                  attch_desc_no2,
                  attch_no3,
                  attch_desc_no3,
                  attch_no4,
                  attch_desc_no4,
                  attch_no5,
                  attch_desc_no5,
                  flex_no ;             
     Exception
      When Others Then
       Raise_Application_Error(-20526,'Error Ias_V_Ny_Itm_Attach_Movement From Ias_Itm_Attach_Movement '||SqlErrm);
     End ;     
     Begin
      Update Ias_V_Ny_Itm_Attach_Movement Q_Outer
       Set Flex_Field = ( Select Flex_Field
                           From Ias_Itm_Attach
                            Where Flex_No = Q_Outer.Flex_No ) ;
                 
     Exception
      When Others Then
       Raise_Application_Error(-20527,'Error When Update Flex Field In Ias_V_Ny_Itm_Attach_Movement '||SqlErrm);
     End ;
 End If;        
End Inv_Close_Prc ;                                     
--==============================================================================
Procedure Insrt_Other_Table( p_stk_cur     In VARCHAR2 ,
                             p_user_no     In NUMBER   ,
                             P_Brn_No      In NUMBER   ,
                             P_Brn_Year    In NUMBER   ,
                             P_Frst_Date   In DATE     ,
                             p_stk_rate    In NUMBER   ,
                             p_cst_type    In NUMBER   ,
                             p_wtavg_type  In NUMBER   ,
                                                 p_cmp_no      In NUMBER   ,
                                                 p_brn_usr     In NUMBER   ) Is 

  cnt NUMBER;
  V_Cons_Name_Pk Varchar2(250);
Begin                                             
   
   Begin
       Select 1 Into Cnt From Gr_Note Where RowNum <= 1  ;
   Exception 
     When Others Then
       cnt := 0 ;
   End ;
   
    Begin
        Select Constraint_Name Into V_Cons_Name_Pk 
          From User_Constraints
         Where Table_Name      = 'IAS_OPEN_STOCK' 
           And Constraint_Type = 'P'
           And STATUS = 'DISABLED';         
    Exception When Others Then
          V_Cons_Name_Pk := Null;
    End;
                        
    If V_Cons_Name_Pk Is Not Null Then
        Begin
            Execute Immediate 'Alter Table IAS_OPEN_STOCK  Enable Constraint '||V_Cons_Name_Pk;
        Exception When Others Then
            Raise_Application_Error(-20531,'Unable To Enable Constraint '||V_Cons_Name_Pk||Chr(13)||SqlErrm);
        End;
        
        Begin
            Execute Immediate 'ALTER TABLE IAS_OPEN_STOCK DROP CONSTRAINT IASOPSTK_UQ';
        Exception When Others Then
            Raise_Application_Error(-20532,'Unable To DROP Constraint IASOPSTK_UQ , '||Chr(13)||SqlErrm);
        End;
        
    End If;
    
   If Nvl(Cnt,0) = 0 Then
        
        
        Begin
             IAS_Itm_Inv_Pkg.Insrt_Gr_Mst ( p_doctype        => 0                   ,
                                                                                    p_gr_no          => 0                   ,
                                                                                    p_g_ser          => 0                   ,
                                                                                    p_doc_DATE       => P_Frst_Date         ,    
                                                                                    p_a_code         => Null                ,
                                                                                    p_acy            => p_stk_cur           ,
                                                                                    p_acrate         => p_stk_rate          ,
                                                                                    p_stkrate        => p_stk_rate          ,                                                      
                                                                                    p_gramt          => 0                   ,
                                                                                    p_pi_no          => Null                ,
                                                                                    P_Cc_Code        => Null                ,
                                                                                    P_Pj_No          => Null                ,
                                                                                    P_Actv_No        => Null                ,
                                                                                    p_w_code         => Null                ,
                                                                                    p_refno          => Null                ,
                                                                                    p_desc           => ias_gen_pkg.get_prompt(p_user_no ,1647),
                                                                                    p_cflag          => 1                   ,
                                                                                    p_pending        => Null                ,
                                                                                    p_pur_type       => Null                , 
                                                                                    p_driver_name    => Null                ,
                                                                                    p_car_no         => Null                ,
                                                                                    p_work_shop      => Null                ,
                                                                                    p_doc_ser        => Null                , 
                                                                                    p_doc_no         => Null                , 
                                                                                    p_rt_out         => Null                , 
                                                                                    p_out_diff_a_code=> Null                ,
                                                                                    p_out_diff_a_cy  => Null                ,
                                                                                    p_out_diff_amt   => Null                ,
                                                                                    p_user_no        => p_user_no           ,
                                                                                    p_Brn_No         => P_Brn_No            ,
                                                                                    p_Brn_Year       => P_Brn_Year         ,
                                                                            P_CMP_NO   =>      P_CMP_NO,
                                                                            P_BRN_USR   =>     P_BRN_USR );
        Exception
          When Others Then
             Raise_Application_Error(-20533,'Error When Inserting Into gr_note '||SqlErrm);
        End ;
  End If ;
  
  /*
  If (p_cst_type = 2 And p_wtavg_type In (2,3) ) Or p_cst_type = 1 Then
          Begin
              Insert Into gr_detail(a_cy          ,
                                    ac_rate       ,
                                    cp_qty        ,
                                    free_qty      ,
                                    gr_no         ,
                                    g_ser         ,
                                    i_code        ,
                                    i_qty         ,
                                       Itm_Unt      , 
                                       p_qty        ,  
                                       p_size        ,
                                       pi_no         ,
                                       pi_type       ,
                                       stk_rate      ,
                                       w_code        ,
                                       Whg_Code      ,
                                       rec_no        ,
                                       expire_DATE   , 
                                       batch_no      ,
                                       c_price       , 
                                       stk_cost      ,
                                       wt_avg_before , 
                                       wt_avg_after  ,
                                       gr_DATE       ,
                                       use_serialno  ,
                                       doc_ser       ,
                                       rcrd_no       ,
                                       doc_sequence  ,
                                       brn_no        ,
                                       brn_year      ,
                                 CMP_NO   ,
                                                           BRN_USR)
                                 Select   p_stk_cur    ,
                                                                p_stk_rate            ,  
                                                                p_qty                 ,
                                                                0                     ,
                                                                0                     ,
                                                                0                     ,
                                                                a.i_code              ,
                                                                i_qty                 ,
                                                                b.Itm_Unt              ,
                                                                p_qty                 ,
                                                                b.p_size              ,
                                                                0                     ,
                                                                0                     ,
                                                                p_stk_rate            ,
                                                                b.w_code              ,
                                                                Ias_Wcode_Pkg.Get_Whg_Code  (b.W_Code),
                                                                ias_recno_seq.NextVal ,
                                                                expire_date           ,
                                                                batch_no              ,
                                                                Primary_cost          ,
                                                                primary_cost          ,
                                                                primary_cost          ,
                                                                primary_cost          ,
                                                                P_Frst_Date                ,
                                                                a.use_serialno        ,
                                                                0                     ,
                                                                a.rcrd_no             ,
                                                                a.doc_sequence,
                                                                a.Brn_No     ,
                                                                a.Brn_Year   ,
                                    a.cmp_no  ,
                                                                a.brn_usr
                                        From  ias_open_stock a,Ias_itm_Wcode b
                                        Where a.i_code  = b.i_code 
                                         And  a.w_code  = b.W_Code ;
          Exception
            When Others Then
              Raise_Application_Error(-20534,'Error When Inserting Into gr_detail '||SqlErrm);
          End ;  
  ElsIf ( p_cst_type = 2 And p_wtavg_type = 1 ) Then
  */
          Begin
              Insert Into gr_detail(a_cy          ,
                                    ac_rate       ,
                                    cp_qty        ,
                                    free_qty      ,
                                    gr_no         ,
                                    g_ser         ,
                                    i_code        ,
                                    i_qty         ,
                                      Itm_Unt        , 
                                       p_qty         ,  
                                       p_size        ,
                                       pi_no         ,
                                       pi_type       ,
                                       stk_rate      ,
                                       w_code        ,
                                       Whg_Code      ,
                                       rec_no        ,
                                       expire_DATE   , 
                                       batch_no      ,
                                       c_price       , 
                                       stk_cost      ,
                                       wt_avg_before , 
                                       wt_avg_after  ,
                                       gr_DATE       ,
                                       use_serialno  ,
                                       doc_ser       ,
                                       rcrd_no       ,
                                       doc_sequence  ,
                                       mov_py_flg    ,
                                       brn_no        ,
                                       brn_year      ,
                                CMP_NO   ,
                                                        BRN_USR)
                                 Select   p_stk_cur    ,
                                                                p_stk_rate            ,  
                                                                p_qty                 ,
                                                                0                     ,
                                                                0                     ,
                                                                0                     ,
                                                                i_code              ,
                                                                i_qty                 ,
                                                                Itm_Unt               ,
                                                                p_qty                 ,
                                                                p_size                ,
                                                                0                     ,
                                                                0                     ,
                                                                p_stk_rate            ,
                                                                w_code                ,
                                                                Ias_Wcode_Pkg.Get_Whg_Code  (W_Code),
                                                                ias_recno_seq.NextVal ,
                                                                expire_DATE           ,
                                                                batch_no              ,
                                                                Nvl(Stk_Cost,0)          ,
                                                                Nvl(Stk_Cost,0)          ,
                                                                Nvl(Stk_Cost,0)          ,
                                                                Nvl(Stk_Cost,0)          ,
                                                                P_Frst_Date                ,
                                                                use_serialno        ,
                                                                0                     ,
                                                                rcrd_no             ,
                                                                doc_sequence,
                                                                mov_py_flg ,
                                                                Brn_No     ,
                                                                Brn_Year   ,
                                CMP_NO   ,
                                                        BRN_USR
                                        From  ias_open_stock ;
          Exception
            When Others Then
              Raise_Application_Error(-20534,'Error When Inserting Into gr_detail '||SqlErrm);
          End ;
--End If ; 
  Begin
    Insert Into item_movement( doc_no      , 
                               doc_type    ,
                               free_qty    , 
                               gr_no       , 
                               i_code      , 
                               i_cost      ,
                               a_cy        ,
                               i_DATE      , 
                               i_qty       ,
                               Itm_Unt      , 
                               p_qty       , 
                               p_size      , 
                               pf_qty      , 
                               rcrd_no     , 
                               serial      , 
                               stk_cost    ,   
                               STK_RATE,
                                                           AC_RATE,
                               w_code      ,
                               Whg_Code    ,
                               expire_DATE , 
                               batch_no    ,
                               in_out      ,
                               doc_ser     ,
                               a_desc      ,
                               doc_sequence,
                               brn_no      ,
                               brn_year    ,
                               CMP_NO   ,
                               BRN_USR ,
                               C_Code
                               )
     Select   0                  ,
                        0                  ,
                        0                  ,
                        0                  ,
                        i_code             ,
                        stk_cost           ,
                        p_stk_cur          ,
                        P_Frst_Date        ,
                        i_qty              , 
                        Itm_Unt             ,  
                        p_qty              , 
                        p_size             ,
                        0                  , 
                        rcrd_no            ,
                        Ias_serial_Seq.NextVal ,
                        stk_cost            , 
                        p_stk_rate,
                        p_stk_rate,
                        w_code              ,
                        Ias_Wcode_Pkg.Get_Whg_Code  (W_Code),
                        expire_DATE         , 
                        batch_no            ,
                        1                   ,
                        0                   ,
                        ias_gen_pkg.get_prompt(p_user_no ,1647),
                        doc_sequence,
                        Brn_No   ,
                        Brn_Year ,
                        CMP_NO   ,
                        BRN_USR ,
                        C_Code
                        From Ias_Open_Stock ;
  Exception 
       When Others Then
         Raise_Application_Error(-20535,'Error When Inserting Into item_movement '||SqlErrm);
  End;   
  
  
  Select Count(1) into cnt From Ias_v_ny_Ias_Itm_Wcode;
  
  If cnt = 0 then
   
          --## Insert open stock Into Ias_Itm_Wcode
          Begin
              Insert Into Ias_v_ny_Ias_Itm_Wcode(i_code  ,
                                                       Itm_Unt  ,
                                                       p_size  ,
                                                       w_code  ,
                                                       avl_qty ,
                                                       Whg_Code,
                                                   Primary_Cost,
                                                   I_Cwtavg,
                                                   Inactive)  
                                                       Select i_code           ,
                                                              Itm_Unt           ,
                                                              p_size           ,
                                                              w_code           ,
                                                              Nvl(Sum(p_qty),0),
                                                              Ias_Wcode_Pkg.Get_Whg_Code  (W_Code),
                                      MIN(Nvl(Stk_Cost,0)),
                                      MIN(Nvl(Stk_Cost,0)),
                                      0
                                                        From Ias_v_ny_ias_open_stock 
                                                         Group By i_code,Itm_Unt,p_size,w_code;
          Exception 
              When others then
            Raise_Application_Error(-20536,'Error When Inserting Into Ias_v_ny_Ias_Itm_Wcode From ias_v_ny_ias_open_stock '||SqlErrm);
          End;
  End If;
   
End Insrt_Other_Table;

--==============================================================================
Procedure Upd_Prim_Wtavg( p_cst_type    In NUMBER ,
                          p_wtavg_type  In NUMBER ,
                          P_Close_Inc_Dtl In Number Default 0 ) Is
Begin
   If  p_cst_type =  2 And p_wtavg_type = 1 Then -- Wtavg & By Item
       Begin
         Update Ias_v_ny_Ias_Itm_Mst a 
          Set i_cwtavg = ( Select Nvl(i_cwtavg,0)
                            From Ias_Itm_Mst 
                             Where I_Code = A.I_Code )
             Where Exists( Select  1 From Ias_Itm_Mst
                            Where Ias_Itm_Mst.I_Code = A.I_Code
                             And RowNum <=1  ) ;
       Exception
         When Others Then  
           Raise_Application_Error(-20534,'Error When Update  ias_v_ny_Ias_Itm_Mst From Ias_Itm_Mst '||SqlErrm);
       End ;
     ElsIf p_cst_type =  2 And p_wtavg_type In (2,3) Then -- Wtavg & By Item + Wcode
           Begin
                 Update ias_v_ny_ias_itm_wcode a 
                   Set i_cwtavg = ( Select Nvl(i_cwtavg,0)
                                     From ias_itm_wcode
                                      Where i_code = a.I_code 
                                       And  w_code = a.w_code 
                                       And  Itm_Unt=a.Itm_Unt)
                      Where Exists( Select  1 From Ias_Itm_Mst
                                      Where Ias_Itm_Mst.i_code = a.i_code
                                       And RowNum <= 1  ) ;
           Exception
              When Others Then  
                Raise_Application_Error(-20534,'Error When Update  ias_v_ny_Ias_Itm_Mst From Ias_Itm_Mst '||SqlErrm);
           End ;    
   End If ;      

   If p_cst_type = 2 Then --wt_avg
      If p_wtavg_type = 1 Then  
              Begin
                Update ias_v_ny_Ias_Itm_Mst 
                   Set primary_cost = Nvl(i_cwtavg,0) ;
              Exception
                 When Others Then
                   Raise_Application_Error(-20536,'Error When Update primary cost to equal i_cwtavg '||SqlErrm);
              End ;
      ElsIf p_wtavg_type In (2,3) Then  
            Begin
               Update ias_v_ny_ias_itm_wcode 
                  Set primary_cost = Nvl(i_cwtavg,0)* Nvl(p_size,1);
            Exception
              When Others Then
                Raise_Application_Error(-20535,'Error When Update primary cost  In ias_v_ny_ias_itm_wcode View '||SqlErrm);
            End ;  
      End  If ;
   Else --fifo,lifo
 --##-------------------------------------------------------------------------------------------##--
        --## Update Cost To Items Not Have Any Avl_Qty 
        Begin
         Update Ias_V_Ny_Ias_Itm_Wcode  
             Set Primary_Cost= Last_Incoming_Price ( P_Wtavg_Type , 
                                                     I_Code       ,
                                                     1            , 
                                                     W_Code       , 
                                                     0            ) 
         Where Itm_Unt = Ias_Itm_Pkg.Get_Icode_Min_Unit  (P_I_Code => I_Code );
        Exception
         When Others Then
          Raise_Application_Error(-20539,'Error When Update Primary Cost To Equal Last_Incoming_Price '||Chr(13)||SqlErrm);   
        End ;
        
        Begin
         Update Ias_V_Ny_Ias_Itm_Mst   
             Set Primary_Cost= Last_Incoming_Price ( P_Wtavg_Type , 
                                                     I_Code       ,
                                                     1            , 
                                                     Null         , 
                                                     0            )  ;
        Exception
         When Others Then
          Raise_Application_Error(-20539,'Error When Update Primary Cost To Equal Last_Incoming_Price '||Chr(13)||SqlErrm);   
        End ;
--##-------------------------------------------------------------------------------------------##--        
       Begin
          Update Ias_V_Ny_Ias_Open_Stock  
             Set Stk_Cost=(Select (Sum((cp_qty/p_size)*stk_cost)/Sum(cp_qty))
                                       From gr_detail 
                                      Where gr_detail.I_Code = Ias_V_Ny_Ias_Open_Stock.I_Code 
                                        And gr_detail.W_Code = Ias_V_Ny_Ias_Open_Stock.W_Code
                                        And gr_detail.Expire_Date = Ias_V_Ny_Ias_Open_Stock.Expire_Date
                                        And gr_detail.Batch_No = Ias_V_Ny_Ias_Open_Stock.Batch_No
                                        And cp_qty<>0 ) ;
        Exception
       When Others Then
        Raise_Application_Error(-20537,'Error When Update primary cost to equal i_cwtavg '||SqlErrm);   
        End ;
            Begin
                Update ias_v_ny_ias_itm_wcode Set primary_cost = nvl(primary_cost,0) * nvl(P_size,1);
            Exception
                 When Others Then  
                 Raise_Application_Error(-20535,'Error When Update ias_v_ny_Ias_Itm_Mst From Ias_Itm_Mst '||SqlErrm);
            End ;
   
   End If;
   
   If (p_cst_type = 2 And p_wtavg_type In (2,3) )   Then -- wtavg & By Item + Wcode   
      Begin
       UPDATE ias_v_ny_ias_open_stock a
          Set stk_cost  = ( Select Nvl(I_Cwtavg,0)
                              From Ias_Itm_Wcode
                             Where i_code = a.i_code 
                               And w_code = a.w_code 
                               And  Itm_Unt=a.Itm_Unt);
      Exception
        When Others Then
          Raise_Application_Error(-20538,'Error When Update stk_cost In ias_v_ny_ias_open_stock '||SqlErrm);
      End ;
   ElsIf p_cst_type = 2 And p_wtavg_type = 1 And Nvl(P_Close_Inc_Dtl,0) = 0 Then
      Begin
       UPDATE ias_v_ny_ias_open_stock a
          Set stk_cost  = ( Select Nvl(primary_cost,0)
                              From Ias_v_ny_Ias_Itm_Mst
                             Where i_code = a.i_code  ) ;
      Exception
        When Others Then
          Raise_Application_Error(-20538,'Error When Update stk_cost In ias_v_ny_ias_open_stock '||SqlErrm);
      End ;  
   End If ;
End Upd_Prim_Wtavg;
--==============================================================================
PROCEDURE Cancel_Inv_Close_Prc (P_Ny_Usr In VARCHAR2)
Is
    Tmp_Table_Name VARCHAR2(100);
    V_SEQ Number;
    Sql_Qry VARCHAR2(5000);
Begin
    --##--------------------------------------------------------------------##--
    
    Sql_Qry:=' Select '||P_Ny_Usr||'.IAS_DOC_SEQ.Nextval From Dual';
    
    Execute Immediate Sql_Qry Into V_SEQ;
    
    --##--------------------------------------------------------------------##--
    Tmp_Table_Name:='IAS_OPEN_STOCK_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.IAS_OPEN_STOCK';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    
    Tmp_Table_Name:='ITEM_MOVEMENT_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.ITEM_MOVEMENT';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    
    Tmp_Table_Name:='GR_NOTE_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.GR_NOTE';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    
    Tmp_Table_Name:='GR_DETAIL_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.GR_DETAIL';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    
    Tmp_Table_Name:='IAS_ITM_WCODE_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.IAS_ITM_WCODE';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    
    Tmp_Table_Name:='IAS_ITEM_SERIALNO_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.IAS_ITEM_SERIALNO';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    
    Tmp_Table_Name:='IAS_ITM_ATTACH_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.IAS_ITM_ATTACH';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    Tmp_Table_Name:='IAS_ITM_ATTACH_MOVEMENT_'||V_SEQ;
        
    Sql_Qry:='Create Table '||P_Ny_Usr||'.'||Tmp_Table_Name||' As Select * From '||P_Ny_Usr||'.IAS_ITM_ATTACH_MOVEMENT';
                    
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
    Sql_Qry:='Delete From '||P_Ny_Usr||'.IAS_ITM_WCODE';
    Execute Immediate Sql_Qry;
    
    Sql_Qry:='Delete From '||P_Ny_Usr||'.IAS_OPEN_STOCK';
    Execute Immediate Sql_Qry;
    
    Sql_Qry:='Delete From '||P_Ny_Usr||'.ITEM_MOVEMENT Where Doc_Type = 0';
    Execute Immediate Sql_Qry;
    
    Sql_Qry:='Delete From '||P_Ny_Usr||'.GR_NOTE Where Pi_Type = 0';
    Execute Immediate Sql_Qry;
    
    Sql_Qry:='Delete From '||P_Ny_Usr||'.GR_DETAIL Where Pi_Type = 0';
    Execute Immediate Sql_Qry;
    
    Sql_Qry:='Delete From '||P_Ny_Usr||'.IAS_ITEM_SERIALNO  Where Doc_Type = 0';
    Execute Immediate Sql_Qry;
    
    Sql_Qry:='Delete From '||P_Ny_Usr||'.IAS_ITM_ATTACH';
    Execute Immediate Sql_Qry;
    
    Sql_Qry:='Delete From '||P_Ny_Usr||'.IAS_ITM_ATTACH_MOVEMENT  Where Doc_Type = 0';
    Execute Immediate Sql_Qry;
    --##--------------------------------------------------------------------##--
Exception When Others Then
    Rollback;
    Raise_Application_Error(-20538,'Error In Cancel_Inv_Close_Prc '||SqlErrm);
End Cancel_Inv_Close_Prc;
--==============================================================================
End IAS_Closing_Year_Pkg;
/